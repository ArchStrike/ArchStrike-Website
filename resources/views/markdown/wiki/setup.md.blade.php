
# Instructions for Full Install on Hard Drive

## Booting the ISO

1) [Download the ISO file](/downloads)

2) Write the ISO to a USB Stick (or a cd-rom).

* Linux: `dd if=ArchStrike-openbox-2016.07.21-x86_64.iso of=/dev/sdX bs=40M status=progress`

* Windows: Download [Rufus](https://rufus.akeo.ie/), select your USB device and the download ISO file. **Important: Choose `dd mode` on Rufus after you press the start button or the ISO won't work.**

3) Boot your computer with your USB device (more info [here](http://lifehacker.com/5991848/how-to-boot-from-a-cd-or-usb-drive-on-any-pc))

4) Choose the ArchStrike option on the boot menu, and it will start.

** If you want to install ArchStrike permanently, you can find the instructions down below.**

## Installing ArchStrike on your Hard Drive

An `archstrike-installer` package is available in the repository. It comes preinstalled in the [Live ArchStrike ISO files](/downloads) for installing ArchStrike on your computer.

** Note: This package is a full installer for ArchStrike. If you're looking for a tool just to set up the repository, search AUR for `archstrike-setuptool` **

** To start the install process on the Live ISO: **

* Run `archstrike-installer` on a terminal

or

* Right-click on the desktop and select `Install ArchStrike` option from the menu.

The installer is very user-friendly and it will help you step by step to get a working ArchStrike install.

# Instructions for Arch Linux users

## I. Install the Repository

**Notes**:

* This guide assumes you already have a working copy of [Arch Linux](https://www.archlinux.org)
* Lines beginning with `#` are command line operations that should be run as root or using `sudo`
* Lines beginning with `$` are command line operations that can be run as either root _or_ a user

### 1. Setup the master ArchStrike repository mirror

Add the following to the bottom of your `/etc/pacman.conf`:

```conf
[archstrike]
Server = https://mirror.archstrike.org/$arch/$repo
```

**Note**: x86_64 users should also ensure that the `multilib` repository is enabled.

Refresh the pacman package database by running:

```bash
# pacman -Syy
```

### 2. Bootstrap and install the ArchStrike keyring

Initialize the pacman keyring and start dirmngr, then import and sign the key used to sign the `archstrike-keyring` package:

```bash
# pacman-key --init
# dirmngr < /dev/null
# pacman-key -r 7CBC0D51
# pacman-key --lsign-key 7CBC0D51
```

### 3. Install required packages

Install `archstrike-keyring` and `archstrike-mirrorlist` to import the keyring and setup the mirrorlist:

```bash
# pacman -S archstrike-keyring
# pacman -S archstrike-mirrorlist
```

### 4. Configure pacman to use the mirrorlist

Open `/etc/pacman.conf` and replace the following block you added above:

```conf
[archstrike]
Server = https://mirror.archstrike.org/$arch/$repo
```

with a new block that uses the mirrorlist instead:

```conf
[archstrike]
Include = /etc/pacman.d/archstrike-mirrorlist
```

**Note**: To use the `archstrike-testing` repository you should _also_ add the following block (See the [repositories](/wiki/repositories) page for more information):

```conf
[archstrike-testing]
Include = /etc/pacman.d/archstrike-mirrorlist
```

Refresh the pacman package database again to reflect the changes above by running:

```bash
# pacman -Syy
```

## II. Groups and Packages

The list of ArchStrike packages from each repository can be viewed by running:

```bash
$ pacman -Sl archstrike
$ pacman -Sl archstrike-testing
```

The list of ArchStrike groups available can be viewed by running:

```bash
$ pacman -Sg | grep archstrike
```

The list of packages in a specific group can be viewed by running:

```bash
$ pacman -Sgg | grep archstrike-<groupname>
```
