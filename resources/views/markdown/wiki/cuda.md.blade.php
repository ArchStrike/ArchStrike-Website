# How to setup NVIDIA CUDA to work with cudahashcat

This guide will be explaining how to get CUDA running on your ArchStrike install in order to use cudahashcat.

## Installing NVIDIA Drivers

To install CUDA you first need the proprietary NVIDIA driver for your GPU.

For more info about installing that, [check out this link to Arch Wiki](https://wiki.archlinux.org/index.php/NVIDIA#Installation)

It is a step-by-step guide for installing the driver with necessary instructions. 

## Installing CUDA 

For cudahashcat to work, you need the cuda driver and runtime libraries.

[Check out this Arch Wiki Link for more info on installation](https://wiki.archlinux.org/index.php/GPGPU#CUDA)

## Using cudahashcat

```bash
# pacman -S cudahashcat
```

will install cudahashcat from the ArchStrike repositories. After that, you can check out [their wiki](https://hashcat.net/wiki/) for more info about usage.
