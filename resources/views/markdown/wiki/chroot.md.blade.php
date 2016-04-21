# Testing PKGBUILD files

You can contribute to the project by fixing problems in PKBUILD files which are used to build packages. The best way to build your PKGBUILD's is in a clean chroot environment.

## Building the chroot environment on your Arch Linux install

Please refer to the Arch Wiki which has an amazing entry all about this: https://wiki.archlinux.org/index.php/DeveloperWiki:Building_in_a_Clean_Chroot

## Configuring pacman and makepkg configs on your new chroot

You will need to edit ~/.makepkg.conf and ~/.pacman.conf on your newly-built chroot. You can take the samples on our github page: https://github.com/ArchStrike/pacman-config/
