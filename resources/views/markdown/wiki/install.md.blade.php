## Beginner's Guide to Installing Arch Linux


This document will guide you through the process of installing [[Arch Linux]] using the [https://projects.archlinux.org/arch-install-scripts.git/ Arch Install Scripts]. Before installing, you are advised to skim over the [[FAQ]].

The community-maintained [[Main page|ArchWiki]] is the primary resource that should be consulted if issues arise. The [[IRC channel]] (irc://irc.freenode.net/#archlinux) and the [https://bbs.archlinux.org/ forums] are also excellent resources if an answer cannot be found elsewhere. In accordance with [[the Arch Way]], you are encouraged to type {{ic|man ''command''}} to read the [[man page]] of any command you are unfamiliar with.

== Preparation ==

Arch Linux should run on any [[Wikipedia:P6 (microarchitecture)|i686]] compatible machine with a minimum of 256 MB RAM. A basic installation with all packages from the {{Grp|base}} group should take less than 800 MB of disk space.

See [[:Category:Getting and installing Arch]] for instructions on downloading the installation medium, and methods for booting it to the target machine(s). This guide assumes you use the latest available version.

== Boot the installation medium ==

Point the current boot device to the drive containing the Arch installation media. This is typically achieved by pressing a key during the [[Wikipedia:Power-on self test|POST]] phase, as indicated on the splash screen. Refer to your motherboard's manual for details.

When the Arch menu appears, select ''Boot Arch Linux'' and press {{ic|Enter}} to enter the installation environment. See [https://projects.archlinux.org/archiso.git/tree/docs/README.bootparams README.bootparams] for a list of [[Kernel parameters#Configuration|boot parameters]].

You will be logged in as the root user and presented with a [[Zsh]] shell prompt. ''Zsh'' provides advanced [http://zsh.sourceforge.net/Guide/zshguide06.html tab completion] and other features as part of the [http://grml.org/zsh/ grml config]. For [[create|modifying or creating]] configuration files, typically in {{ic|/etc}}, [[nano#Usage|nano]] and [[vim#Usage|vim]] are suggested.

=== UEFI mode ===

In case you have a [[UEFI]] motherboard with UEFI mode enabled, the CD/USB will automatically launch Arch Linux via [http://www.freedesktop.org/wiki/Software/systemd/systemd-boot/ systemd-boot].

To verify you are booted in UEFI mode, check that the following directory is populated:

 # ls /sys/firmware/efi/efivars

See [[UEFI#UEFI Variables]] for details.

=== Set the keyboard layout ===

The default [[Keyboard_configuration_in_console|console keymap]] is set to [[Wikipedia:File:KB United States-NoAltGr.svg|us]]. Available choices can be listed with {{ic|ls /usr/share/kbd/keymaps/**/*.map.gz}}. 

{{Note|{{ic|localectl list-keymaps}} does not work due to bug {{Bug|46725}}.}}

For example, to change the layout to {{ic|de-latin1}}, run:

 # loadkeys ''de-latin1''

If certain characters appear as white squares or other symbols, change the [[Console fonts|console font]]. For example:

 # setfont ''lat9w-16''

=== Connect to the Internet ===

; Wired

The [[dhcpcd]] daemon is enabled on boot for wired devices, and will attempt to start a connection. To access captive portal login forms, use the [[ELinks]] browser. 

Verify a connection was established, for example with ''ping''. If none is available, proceed to [[Network configuration|configure the network]]; the examples below use [[netctl]] to this purpose. To prevent conflicts, [[stop]] the ''dhcpcd'' service (replacing {{ic|enp0s25}} with the correct wired interface):

 # systemctl stop dhcpcd@''enp0s25''.service

[[Network configuration#Device names|Interfaces]] can be listed using {{ic|ip link}}, or {{ic|iw dev}} for wireless devices. They are prefixed with {{ic|en}} (ethernet), {{ic|wl}} (WLAN), or {{ic|ww}} (WWAN).

; Wireless

List [[Wireless_network_configuration#Getting_some_useful_information|available networks]], and make a connection for a specified interface:

 # wifi-menu -o ''wlp2s0''

The resulting configuration file is stored in {{ic|/etc/netctl}}. For networks which require both a username and password, see [[WPA2 Enterprise#netctl]].

; Other

Several example profiles, such as for configuring a [[Network configuration#Static IP address|static IP address]], are available. Copy the required one to {{ic|/etc/netctl}}, for example {{ic|ethernet-static}}:

 # cp /etc/netctl/examples/''ethernet-static'' /etc/netctl

Adjust the copy as needed, and enable it:

 # netctl start ''ethernet-static''

=== Update the system clock ===

Use [[systemd-timesyncd]] to ensure that your system clock is accurate. To start it:

 # timedatectl set-ntp true

To check the service status, use {{ic|timedatectl status}}.

== Prepare the storage devices ==

{{Warning|
* In general, partitioning or formatting will make existing data inaccessible and subject to being overwritten, i.e. destroyed, by subsequent operations. For this reason, all data that needs to be preserved must be backed up before proceeding.
* If dual-booting with an existing installation of Windows on a UEFI/GPT system, avoid reformatting the UEFI partition, as this includes the Windows ''.efi'' file required to boot it. Furthermore, Arch must follow the same firmware boot mode and partitioning combination as Windows. See [[Dual boot with Windows#Important information]].}}

In this step, the storage devices that will be used by the new system will be prepared. Read [[Partitioning]] for a more general overview.

Users intending to create stacked block devices for [[LVM]], [[disk encryption]] or [[RAID]], should keep those instructions in mind when preparing the partitions. If intending to install to a USB flash key, see [[Installing Arch Linux on a USB key]].

=== Identify the devices ===

The first step is to identify the devices where the new system will be installed. The following command will show all the available devices:

 # lsblk

This will list all devices connected to your system along with their partition schemes, including that used to host and boot live Arch installation media (e.g. a USB drive). Not all devices listed will therefore be viable or appropriate mediums for installation. Results ending in {{ic|rom}}, {{ic|loop}} or {{ic|airoot}} can be ignored.

Devices (e.g. hard disks) will be listed as {{ic|sd''x''}}, where {{ic|''x''}} is a lower-case letter starting from {{ic|a}} for the first device ({{ic|sda}}), {{ic|b}} for the second device ({{ic|sdb}}), and so on. Existing partitions on those devices will be listed as {{ic|sd''xY''}}, where {{ic|''Y''}} is a number starting from {{ic|1}} for the first partition, {{ic|2}} for the second, and so on. In the example below, only one device is available ({{ic|sda}}), and that device has only one partition ({{ic|sda1}}):

 NAME            MAJ:MIN RM   SIZE RO TYPE MOUNTPOINT
 sda               8:0    0    80G  0 disk
 └─sda1            8:1    0    80G  0 part

The {{ic|sd''xY''}} convention will be used in the examples provided below for partition tables, partitions, and file systems. As they are just examples, it is important to ensure that any necessary changes to device names, partition numbers, and/or partition sizes (etc.) are made. Do not just blindly copy and paste the commands.

If the existing partition scheme does not need to be changed, skip to [[#Format the file systems and enable swap]], otherwise continue reading the following section.

=== Partition table types ===

If you are installing alongside an existing installation (i.e. dual-booting), a partition table will already be in use. If the devices are not partitioned, or the current partitions table or scheme needs to be changed, you will first have to determine the partition tables (one for each device) in use or to be used.

There are two types of partition table:

* [[GPT]]
* [[MBR]]

Any existing partition table can be identified with the following command for each device:

 # parted /dev/sd''x'' print

=== Partitioning tools ===

{{Warning|Using a partitioning tool that is incompatible with your partition table type will likely result in the destruction of that table, along with any existing partitions/data.}}

For each device to be partitioned, a proper tool must be chosen according to the partition table to be used. Several partitioning tools are provided by the Arch installation medium, including:

* [[parted]]: GPT and MBR
* [[fdisk#Usage|fdisk]], '''cfdisk''', '''sfdisk''': GPT and MBR
* [[gdisk]], '''cgdisk''', '''sgdisk''': GPT

Devices may also be partitioned before booting the installation media, for example through tools such as [[GParted]] (also provided as a [http://gparted.sourceforge.net/livecd.php live CD]).

==== Using parted in interactive mode ====

All the examples provided below use ''parted'', because it can be used for both UEFI/GPT and BIOS/MBR. It will be launched in ''interactive mode'', which simplifies the partitioning process and reduces unnecessary repetition by automatically applying all partitioning commands to the specified device.

In order to start operating on a device, execute:

 # parted /dev/sd''x''

You will notice that the command-line prompt changes from a hash ({{ic|#}}) to {{ic|(parted)}}: this also means that the new prompt is not a command to be manually entered when running the commands in the examples.

To see a list of the available commands, enter:

 (parted) help

When finished, or if wishing to implement a partition table or scheme for another device, exit from parted with:

 (parted) quit

After exiting, the command-line prompt will change back to {{ic|#}}.

=== Create new partition table ===

You need to (re)create the partition table of a device when it has never been partitioned before, or when you want to change the type of its partition table. Recreating the partition table of a device is also useful when the partition scheme needs to be restructured from scratch.

Open each device whose partition table must be (re)created with:

 # parted /dev/sd''x''

To then create a new GPT partition table for UEFI systems, use the following command:

 (parted) mklabel gpt

To create a new MBR/msdos partition table for BIOS systems instead, use:

 (parted) mklabel msdos

=== Partition schemes ===

You can decide the number and size of the partitions the devices should be split into, and which directories will be used to mount the partitions in the installed system (also known as ''mount points''). The mapping from partitions to directories is the [[Partitioning#Partition scheme|partition scheme]], which must comply with the following requirements:

* At least a partition for the {{ic|/}} (''root'') directory '''must''' be created.
* When using a UEFI motherboard, one [[EFI System Partition]] '''must''' be created.

In the examples below it is assumed that a new and contiguous partitioning scheme is applied to a single device. Some optional partitions will also be created for the {{ic|/boot}} and {{ic|/home}} directories which otherwise would simply be contained in the {{ic|/}} partition. See the [[Arch filesystem hierarchy]] for an explanation of the purpose of the various directories. Also the creation of an optional partiton for [[swap space]] will be illustrated.

If not already open in a ''parted'' interactive session, open each device to be partitioned with:

 # parted /dev/sd''x''

The following command will be used to create partitions:

 (parted) mkpart ''part-type'' ''fs-type'' ''start'' ''end''

* {{ic|''part-type''}} is one of {{ic|primary}}, {{ic|extended}} or {{ic|logical}}, and is meaningful only for MBR partition tables.
* {{ic|''fs-type''}} is an identifier chosen among those listed by entering {{ic|help mkpart}} as the closest match to the file system that you will use in [[#Format the file systems and enable swap]]. The ''mkpart'' command does not actually create the file system: the {{ic|''fs-type''}} parameter will simply be used by ''parted'' to set a 1-byte code that is used by boot loaders to "preview" what kind of data is found in the partition, and act accordingly if necessary. See also [[Wikipedia:Disk partitioning#PC partition types]].
: {{Tip|Most [[Wikipedia:File_system#Linux|Linux native file systems]] map to the same partition code ([[Wikipedia:Partition type#PID_83h|0x83]]), so it is perfectly safe to e.g. use {{ic|ext2}} for an ''ext4''-formatted partition.}}
* {{ic|''start''}} is the beginning of the partition from the start of the device. It consists of a number followed by a [http://www.gnu.org/software/parted/manual/parted.html#unit unit], for example {{ic|1M}} means start at 1MiB.
* {{ic|''end''}} is the end of the partition from the start of the device (''not'' from the {{ic|''start''}} value). It has the same syntax as {{ic|''start''}}, for example {{ic|100%}} means end at the end of the device (use all the remaining space).

{{Warning|It is important that the partitions do not overlap each other: if you do not want to leave unused space in the device, make sure that each partition starts where the previous one ends.}}

{{Note|''parted'' may issue a warning like:

 Warning: The resulting partition is not properly aligned for best performance.
 Ignore/Cancel?

In this case, read [[Partitioning#Partition alignment]] and follow [[GNU Parted#Alignment]] to fix it.}}

The following command will be used to flag the partition that contains the {{ic|/boot}} directory as bootable:

 (parted) set ''partition'' boot on

* {{ic|''partition''}} is the number of the partition to be flagged (see the output of the {{ic|print}} command).

==== UEFI/GPT examples ====

In every instance, a special bootable [[EFI System Partition]] is required.

If creating a new EFI System Partition, use the following commands (a size of 512MiB is suggested):

 (parted) mkpart ESP fat32 1MiB 513MiB
 (parted) set 1 boot on

The remaining partition scheme is entirely up to you. For one other partition using 100% of remaining space:

 (parted) mkpart primary ext4 513MiB 100%

For separate {{ic|/}} (20GiB) and {{ic|/home}} (all remaining space) partitions:

 (parted) mkpart primary ext4 513MiB 20.5GiB
 (parted) mkpart primary ext4 20.5GiB 100%

And for separate {{ic|/}} (20GiB), swap (4GiB), and {{ic|/home}} (all remaining space) partitions:

 (parted) mkpart primary ext4 513MiB 20.5GiB
 (parted) mkpart primary linux-swap 20.5GiB 24.5GiB
 (parted) mkpart primary ext4 24.5GiB 100%

==== BIOS/MBR examples ====

For a minimum single primary partition using all available disk space, the following command would be used:

 (parted) mkpart primary ext4 1MiB 100%
 (parted) set 1 boot on

In the following instance, a 20GiB {{ic|/}} partition will be created, followed by a {{ic|/home}} partition using all the remaining space:

 (parted) mkpart primary ext4 1MiB 20GiB
 (parted) set 1 boot on
 (parted) mkpart primary ext4 20GiB 100%

In the final example below, separate {{ic|/boot}} (100MiB), {{ic|/}} (20GiB), swap (4GiB), and {{ic|/home}} (all remaining space) partitions will be created:

 (parted) mkpart primary ext4 1MiB 100MiB
 (parted) set 1 boot on
 (parted) mkpart primary ext4 100MiB 20GiB
 (parted) mkpart primary linux-swap 20GiB 24GiB
 (parted) mkpart primary ext4 24GiB 100%

=== Format the file systems and enable swap ===

Once the partitions have been created, each '''must''' be formatted with an appropriate [[file system]], ''except'' for swap partitions. All available partitions on the intended installation device can be listed with the following command:

 # lsblk /dev/sd''x''

With the exceptions noted below, it is recommended to use the {{ic|ext4}} file system:

 # mkfs.ext4 /dev/sd''xY''

''If'' a swap partition has been created, it must be set up and activated with:

 # mkswap /dev/sd''xY''
 # swapon /dev/sd''xY''

Mount the root partition to the {{ic|/mnt}} directory of the live system:

 # mount /dev/sd''xY'' /mnt

Remaining [[Partitioning#Partition_scheme|partitions]] (except ''swap'') may be mounted in any order, after creating the respective mount points. For example, when using a {{ic|/boot}} partition:

 # mkdir -p /mnt/boot
 # mount /dev/sd''xZ'' /mnt/boot

''If'' a new UEFI system partition has been created on a UEFI/GPT system, it '''must''' be formatted with a {{ic|fat32}} file system:

 # mkfs.fat -F32 /dev/sd''xY''

{{ic|/mnt/boot}} is also recommended for mounting the (formatted or already existing) EFI System Partition on a UEFI/GPT system. See [[EFISTUB]] and related articles for alternatives.

== Installation ==

=== Select the mirrors ===

Packages to be installed must be downloaded from mirror servers, which are defined in {{ic|/etc/pacman.d/mirrorlist}}. On the live system, all mirrors are enabled, and sorted by their synchronization status and speed at the time the installation image was created.

The higher a mirror is placed in the list, the more priority it is given when downloading a package. You may want to edit the file accordingly, and move the geographically closest mirrors to the top of the list, although other criteria should be taken into account. See [[Mirrors]] for details.

''pacstrap'' will also install a copy of this file to the new system, so it is worth getting right.

=== Install the base packages ===

The ''pacstrap'' script installs the base system. To build packages from the [[AUR]] or with [[ABS]], the {{Grp|base-devel}} group is also required.

Not all tools from the live installation (see [https://projects.archlinux.org/archiso.git/tree/configs/releng/packages.both packages.both]) are part of the base group. Packages can later be [[install]]ed with ''pacman'', or by appending their names to the ''pacstrap'' command.

 # pacstrap -i /mnt base base-devel

The {{ic|-i}} switch ensures prompting before package installation.

== Configuration ==

=== fstab ===

Generate an [[fstab]] file. The {{ic|-U}} option indicates UUIDs: see [[Persistent block device naming]]. Labels can be used instead through the {{ic|-L}} option.

 # genfstab -U /mnt >> /mnt/etc/fstab

Check the resulting file in {{ic|/mnt/etc/fstab}} afterwards, and edit it in case of errors.

=== Change root ===

Copy netctl profiles in {{ic|/etc/netctl}} to the new system in {{ic|/mnt}} (when applicable), then [[chroot]] to it:

 # arch-chroot /mnt /bin/bash

=== Locale ===

The [[Locale]] defines which language the system uses, and other regional considerations such as currency denomination, numerology, and character sets.

Possible values are listed in {{ic|/etc/locale.gen}}. Uncomment {{ic|en_US.UTF-8 UTF-8}}, as well as other needed localisations. Save the file, and generate the new locales:

 # locale-gen

[[Create]] {{ic|/etc/locale.conf}}, where {{ic|LANG}} refers to the ''first column'' of an uncommented entry in {{ic|/etc/locale.gen}}:

{{hc|1=/etc/locale.conf|2=
LANG=''en_US.UTF-8''
}}

If you [[#Set the keyboard layout|set the keyboard layout]], make the changes persistent in {{ic|/etc/vconsole.conf}}. For example, if {{ic|de-latin1}} was set with ''loadkeys'', and {{ic|lat9w-16}} with ''setfont'', assign the {{ic|KEYMAP}} and {{ic|FONT}} variables accordingly:

{{hc|1=/etc/vconsole.conf|2=
KEYMAP=''de-latin1''
FONT=''lat9w-16''
}}

=== Time ===

Select a [[time zone]]:

 # tzselect

Create the symbolic link {{ic|/etc/localtime}}, where {{ic|Zone/Subzone}} is the {{ic|TZ}} value from ''tzselect'':

 # ln -s /usr/share/zoneinfo/''Zone''/''SubZone'' /etc/localtime

It is recommended to adjust the time skew, and set the time standard to UTC:

 # hwclock --systohc --utc

If other operating systems are installed on the machine, they must be configured accordingly. See [[Time]] for details.

=== Initramfs ===

As [[mkinitcpio]] was run on installation of {{Pkg|linux}} with ''pacstrap'', most users can use the defaults provided in {{ic|mkinitcpio.conf}}.

For special configurations, set the correct [[Mkinitcpio#HOOKS|hooks]] in {{ic|/etc/mkinitcpio.conf}} and [[Mkinitcpio#Image_creation_and_activation|re-generate]] the initramfs image:

 # mkinitcpio -p linux

=== Install a boot loader ===

See [[Boot loaders]] for available choices and configurations. If you have an Intel CPU, install the {{Pkg|intel-ucode}} package, and [[Microcode#Enabling_Intel_microcode_updates|enable microcode updates]].

==== UEFI/GPT ====

Here, the installation drive is assumed to be GPT-partioned, and have the [[EFI System Partition]] (gdisk type {{ic|EF00}}, formatted with FAT32) mounted at {{ic|/boot}}.

Install [[systemd-boot]] to the EFI system partition:

 # bootctl install

When successful, create a boot entry as described in [[systemd-boot#Configuration]] (replacing {{ic|$esp}} with {{ic|/boot}}), or adapt the examples in {{ic|/usr/share/systemd/bootctl/}}.

==== BIOS/MBR ====

Install the {{Pkg|grub}} package. To search for other operating systems, also install {{Pkg|os-prober}}:

 # pacman -S grub os-prober

Install the bootloader to the ''drive'' Arch was installed to:

 # grub-install --target=i386-pc ''/dev/sda''

Generate {{ic|grub.cfg}}:

 # grub-mkconfig -o /boot/grub/grub.cfg

See [[GRUB]] for more information.

=== Configure the network ===

The procedure is similar to [[#Connect to the Internet]], except made persistent for subsequent boots. Select '''one''' daemon to handle the network.

==== Hostname ====

Set the [[hostname]] to your liking by [[add]]ing ''myhostname'' to the following file, where ''myhostname'' is the hostname you wish to set:

{{hc|1=/etc/hostname|2=
''myhostname''
}}

It is recommended to [[append]] the same hostname to {{ic|localhost}} entries in {{ic|/etc/hosts}}. See [[Network configuration#Set the hostname]] for details.

==== Wired ====

When only requiring a single wired connection, [[enable]] the [[dhcpcd]] service:

 # systemctl enable dhcpcd@''interface''.service

Where {{ic|''interface''}} is an ethernet [[Network_configuration#Device_names|device name]].

See [[Network configuration#Configure the IP address]] for other available methods.

==== Wireless ====

Install {{Pkg|iw}}, {{Pkg|wpa_supplicant}}, and (for ''wifi-menu'') {{Pkg|dialog}}:

 # pacman -S iw wpa_supplicant dialog

Additional [[Wireless#Installing driver/firmware|firmware packages]] may also be required.

If you used ''wifi-menu'' priorly, repeat the steps '''after''' finishing the rest of this installation and rebooting, to prevent conflicts with the existing processes.

See [[Netctl]] and [[Wireless#Wireless management]] for more information.

=== Root password ===

Set the root [[password]] with:

 # passwd

== Unmount the partitions and reboot ==

Exit from the chroot environment by running {{ic|exit}} or pressing {{ic|Ctrl+D}}. 

Partitions will be unmounted automatically by ''systemd'' on shutdown. You may however unmount manually as a safety measure:

 # umount -R /mnt

If the partition is "busy", you can find the cause with [[fuser]]. Reboot the computer. 

 # reboot

Remove the installation media, or you may boot back into it. You can log into your new installation as ''root'', using the password you specified with ''passwd''.

== Post-installation ==

Your new Arch Linux base system is now a functional GNU/Linux environment ready to be built into whatever you wish or require for your purposes. You are now ''strongly'' advised to read the [[General recommendations]] article, especially the first two sections. Its other sections provide links to post-installation tutorials like setting up a graphical user interface, sound or a touchpad.
