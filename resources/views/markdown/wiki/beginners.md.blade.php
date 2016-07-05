[[Category:Getting and installing Arch]]
[[ar:Beginners' guide]]
[[bg:Beginners' guide]]
[[cs:Beginners' guide]]
[[da:Beginners' guide]]
[[de:Anleitung für Einsteiger]]
[[el:Beginners' guide]]
[[es:Beginners' guide]]
[[fa:راهنمای_تازه‌کاران]]
[[fr:Installation]]
[[he:Beginners' guide]]
[[hr:Beginners' guide]]
[[hu:Beginners' guide]]
[[id:Beginners' guide]]
[[it:Beginners' guide]]
[[ja:ビギナーズガイド]]
[[ko:Beginners' guide]]
[[lt:Beginners' guide]]
[[nl:Beginners' guide]]
[[pl:Beginners' guide]]
[[pt:Beginners' guide]]
[[ro:Ghidul începătorilor]]
[[ru:Beginners' guide]]
[[sk:Beginners' guide]]
[[sr:Beginners' guide]]
[[sv:Nybörjarguiden]]
[[tr:Yeni başlayanlar rehberi]]
[[uk:Beginners' guide]]
[[zh-cn:Beginners' guide]]
[[zh-tw:Beginners' guide]]
{{Related articles start}}
{{Related|:Category:Accessibility}}
{{Related|Help:Reading}}
{{Related|Installation guide}}
{{Related|General recommendations}}
{{Related|General troubleshooting}}
{{Related articles end}}
This document will guide you through the process of installing [[Arch Linux]] using the [https://projects.archlinux.org/arch-install-scripts.git/ Arch Install Scripts]. Before installing, you are advised to skim over the [[FAQ]].

The community-maintained [[Main page|ArchWiki]] is the primary resource that should be consulted if issues arise. The [[IRC channel]] (irc://irc.freenode.net/#archlinux) and the [https://bbs.archlinux.org/ forums] are also excellent resources if an answer cannot be found elsewhere. In accordance with [[the Arch Way]], you are encouraged to type {{ic|man ''command''}} to read the [[man page]] of any command you are unfamiliar with.

{{Tip|
* You can access this page from the live installation using the [[elinks]] browser once [[#Connect to the Internet|connected to the internet]]. For example, you could open this page with elinks in a new [[Wikipedia:Virtual console|virtual console]] as a reference, switching between the console containing the webpage and the console where you are performing the installation as needed.
* You can access the IRC channel from the live installation media using [[irssi]] once connected to the internet. As above, this can be done in a different virtual console if needed.}}

== Preparation ==

Arch Linux should run on any [[Wikipedia:P6 (microarchitecture)|i686]] compatible machine with a minimum of 256 MB RAM. A basic installation with all packages from the {{Grp|base}} group should take less than 800 MB of disk space.

See [[:Category:Getting and installing Arch]] for instructions on downloading the installation medium, and methods for booting it to the target machine(s). This guide assumes you use the latest available version.

After booting into the installation media, you will be automatically logged in as the root user and presented with a [[Zsh]] shell prompt. ''Zsh'' provides advanced [http://zsh.sourceforge.net/Guide/zshguide06.html tab completion] and other features as part of the [http://grml.org/zsh/ grml config]. For [[create|modifying or creating]] configuration files, typically in {{ic|/etc}}, [[nano#Usage|nano]] and [[vim#Usage|vim]] are suggested.

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

Several example profiles, such as for configuring a [[Network configuration#Static IP address|static IP address]], are available. Copy the required one to {{ic|/etc/netctl}}, for example {{ic|''ethernet-static''}}:

 # cp /etc/netctl/examples/''ethernet-static'' /etc/netctl

Adjust the copy as needed, and enable it:

 # netctl start ''ethernet-static''

=== Update the system clock ===

Use [[systemd-timesyncd]] to ensure that your system clock is accurate. To start it:

 # timedatectl set-ntp true

To check the service status, use {{ic|timedatectl status}}.

== Prepare the storage devices ==

{{Warning|In general, partitioning or formatting will make existing data inaccessible and subject to being overwritten, i.e. destroyed, by subsequent operations. For this reason, all data that needs to be preserved must be backed up before proceeding.}}

In this step, the storage devices that will be used by the new system will be prepared. Read [[Partitioning]] for a more general overview.

Users intending to create stacked block devices for [[LVM]], [[disk encryption]] or [[RAID]], should keep those instructions in mind when preparing the partitions. If intending to install to a USB flash key, see [[Installing Arch Linux on a USB key]].

=== Identify the devices ===

[[File_systems#Identify_the_devices|Identify the devices]] where the new system will be installed:

 # lsblk

Not all devices listed are viable mediums for installation; results ending in {{ic|rom}}, {{ic|loop}} or {{ic|airoot}} can be ignored.

{{Note|In the sections below, the {{ic|sd'''xy'''}} notation will be used ({{ic|'''x'''}} for the device, {{ic|'''y'''}} for an existing partition).}}

If the existing partition scheme does not need to be changed, skip to [[#Format the partitions]], otherwise continue reading the following section.

=== Partition the devices ===

Partitioning a hard drive divides the available space into sections that can be accessed independently. The required information is stored in a ''partition table'' using a format such as [[MBR]] or [[GPT]]. Existing tables can be printed with {{ic|parted /dev/sd'''x''' print}} or {{ic|fdisk -l /dev/sd'''x'''}}.

To partition devices, use a [[Partitioning#Partitioning tool|partitioning tool]] compatible to the chosen type of partition table. Incompatible tools may result in the destruction of that table, along with existing partitions or data. Choices include:

{| class="wikitable"
! Name
! MBR
! GPT
! Variants
|-
| [[fdisk]]
| {{Yes}}
| {{Yes}}
| ''sfdisk'', ''cfdisk''
|-
| [[gdisk]]
| {{No}}
| {{Yes}}
| ''cgdisk'', ''sgdisk''
|-
| [[parted]]
| {{Yes}}
| {{Yes}}
| [http://gparted.sourceforge.net/livecd.php GParted]
|-
|}

The mapping from partitions to directories in the installed system, referred to as ''partition layout'' or ''partition scheme'', must comply with the following requirements:

* At least a partition for {{ic|/}}, or ''root'' directory,
* When using a UEFI motherboard, one [[EFI System Partition]].

Examples below assume that a new, contiguous partitioning scheme is applied to a single device in {{ic|/dev/sd'''x'''}}. 

'''Keep in mind that these are examples'''; change device names, partition numbers and partition scheme where necessary. See [[Partitioning#Partition scheme]] for more information.

==== UEFI/GPT ====

A special ''bootable'' [[EFI System Partition]] is required, here assumed in {{ic|/boot}}.

{| class="wikitable"
! Mount point
! Partition
! [[w:GUID_Partition_Table#Partition_type_GUIDs|Partition type (GUID)]]
! Bootable flag
! Suggested size
|-
| /boot
| /dev/sd'''x'''1
| EFI System partition
| Yes
| 260 - 512 MiB
|-
| /swap
| /dev/sd'''x'''2
| Linux swap
| No
| 512 MiB - RAM size
|-
| /
| /dev/sd'''x'''3
| Linux
| No
| 15 - 20 GiB
|-
| /home
| /dev/sd'''x'''4
| Linux
| No
| Remainder of the partition
|}

==== BIOS/MBR ====

{| class="wikitable"
! Mount point
! Partition
! [[w:Partition type|Partition type]]
! Bootable flag
! Suggested size
|-
| /swap
| /dev/sd'''x'''1
| Linux swap
| No
| 512 MiB - RAM size
|-
| /
| /dev/sd'''x'''2
| Linux
| Yes
| 15 - 20 GiB
|-
| /home
| /dev/sd'''x'''3
| Linux
| No
| Remainder of the partition
|}

=== Format the partitions ===

{{Warning|If [[Dual boot with Windows|dual-booting]] with an existing installation of Windows on a UEFI/GPT system, avoid reformatting the UEFI partition, as this includes the Windows ''.efi'' file required to boot it.}}

Once the partitions have been created, each '''must''' be formatted with an appropriate [[file system]], ''except'' for swap partitions. All available partitions on the intended installation device can be listed with the following command:

 # lsblk /dev/sd'''x'''

With the exceptions noted below, it is recommended to use the {{ic|ext4}} file system:

 # mkfs.ext4 /dev/sd'''xy'''

If a swap partition was created, it must be set up and activated with:

 # mkswap /dev/sd'''xy'''
 # swapon /dev/sd'''xy'''

If a '''new''' UEFI system partition has been created on a UEFI/GPT system, it '''must''' be formatted with a {{ic|fat32}} file system:

 # mkfs.fat -F32 /dev/sd'''xy'''

=== Mount the partitions ===

Mount the ''root'' partition to the {{ic|/mnt}} directory of the live system:

 # mount /dev/sd'''xy''' /mnt

Remaining [[Partitioning#Partition_scheme|partitions]] (except ''swap'') may be mounted in any order, after creating the respective mount points. For example, when using a {{ic|/boot}} partition:

 # mkdir -p /mnt/boot
 # mount /dev/sd'''xy''' /mnt/boot

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

Copy netctl profiles in {{ic|/etc/netctl}} to the new system in {{ic|/mnt}} (when applicable), then [[chroot#Change root|chroot]] to it:

 # arch-chroot /mnt /bin/bash

=== Locale ===

The [[Locale]] defines which language the system uses, and other regional considerations such as currency denomination, numerology, and character sets.

Uncomment {{ic|en_US.UTF-8 UTF-8}} in {{ic|/etc/locale.gen}}, as well as other needed localisations. Save the file, and generate the new locales:

 # locale-gen

[[Create]] {{ic|/etc/locale.conf}}, where {{ic|''en_US.UTF-8''}} refers to the '''first column''' of an uncommented entry in {{ic|/etc/locale.gen}}:

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

Because [[mkinitcpio]] was run on installation of {{Pkg|linux}} with ''pacstrap'', most users can use the defaults provided in {{ic|mkinitcpio.conf}}.

For special configurations, set the correct [[Mkinitcpio#HOOKS|hooks]] in {{ic|/etc/mkinitcpio.conf}} and [[Mkinitcpio#Image_creation_and_activation|re-generate]] the initramfs image:

 # mkinitcpio -p linux

=== Install a boot loader ===

See [[Boot loaders]] for available choices and configurations. If you have an Intel CPU, in addition to installing a boot loader, install the {{Pkg|intel-ucode}} package and [[Microcode#Enabling_Intel_microcode_updates|enable microcode updates]].

{| class="wikitable"
! Name
! BIOS
! UEFI
! Dual-boot
|-
| [[grub]]
| {{Yes}}
| {{Yes}}
| {{Yes}}
|-
| [[systemd-boot]]
| {{Yes}}
| {{Yes}}
| {{Yes}}
|-
| [[syslinux]]
| {{Yes}}
| {{Y|Partial}}
| {{Y|Partial}}
|-
| [[EFISTUB]]
| {{No}}
| {{Yes}}
| {{Yes}}
|-
| [[rEFInd]]
| {{No}}
| {{Yes}}
| {{Yes}}
|-
| [[Clover]]
| {{No}}
| {{Yes}}
| {{Yes}}
|-
|}

==== UEFI/GPT Example ====

Here, the installation drive is assumed to be GPT-partioned, and have the [[EFI System Partition]] (gdisk type {{ic|EF00}}, formatted with FAT32) mounted at {{ic|/boot}}.

Install [[systemd-boot]] to the EFI system partition:

 # bootctl install

When successful, create a boot entry as described in [[systemd-boot#Configuration]] (replacing {{ic|$esp}} with {{ic|/boot}}), or adapt the examples in {{ic|/usr/share/systemd/bootctl/}}.

==== BIOS/MBR Example ====

Install the {{Pkg|grub}} package. To search for other operating systems, also install {{Pkg|os-prober}}:

 # pacman -S grub os-prober

Install the bootloader to the ''drive'' Arch was installed to:

 # grub-install --target=i386-pc ''/dev/sda''

Generate {{ic|grub.cfg}}:

 # grub-mkconfig -o /boot/grub/grub.cfg

See [[GRUB]] for more information.

=== Configure the network ===

The procedure is similar to [[#Connect to the Internet]] for the live installation environment, except made persistent for subsequent boots. Select '''one''' daemon to handle the network.

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

Install the {{Pkg|iw}}, {{Pkg|wpa_supplicant}}, and (for ''wifi-menu'') {{Pkg|dialog}} packages:

 # pacman -S iw wpa_supplicant dialog

Additional [[Wireless#Installing driver/firmware|firmware packages]] may also be required. When using ''wifi-menu'', do so after [[#Unmount_the_partitions_and_reboot]].

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

For a list of applications that may be of interest, see [[List of applications]].

