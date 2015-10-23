## I. INSTALL AND CONFIGURE REPOSITORY

#### 1. Configure /etc/pacman.conf:

`# vi /etc/pacman.conf`

Insert the following to the bottom of pacman.conf:

    [archstrike]
    Server = https://mirror.archstrike.org/$arch/$repo

**Note: For x86_64 setups only**  
Uncomment the following lines in pacman.conf to enable multilib:

    [multilib]
    Include = /etc/pacman.d/mirrorlist  

#### 2. Initialize ArchStrike
```# pacman-key --init
# dirmngr < /dev/null
# pacman-key -r B84C1B2F && pacman-key --lsign-key B84C1B2F
```

#### 3. Install keyring and mirrorlist packages
    # pacman -S archstrike-keyring
	# pacman -S archstrike-mirrorlist

#### 4. Configure /etc/pacman.conf to use ArchStrike mirrorlist

`# vi /etc/pacman.conf`

Replace:

    Server = http://mirror.archstrike.org/$arch/$repo

with

    Include = /etc/pacman.d/archstrike-mirrorlist

#### 5. Sync repo with mirror
	# pacman -Syyu


## II. IF UPGRADING FROM TESTING REPOSITORY

#### 1. Update /etc/pacman.conf

    # vi /etc/pacman.conf

Change from

    [archstrike-testing]

Change to

    [archstrike]

#### 2. Sync with repo and update keyring and mirrorlist packages

	# pacman -Syyu
	# pacman -S archstrike-keyring
	# pacman -S archstrike-mirrorlist


## III. INSTALLING PACKAGES

To install all ArchStrike packages

	# pacman -S archstrike

To install packages from a specific group

	# pacman -S archstrike-<group>

To list ArchStrike package groups:

	$ pacman -Sg | grep archstrike
