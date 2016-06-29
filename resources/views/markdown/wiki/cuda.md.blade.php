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

Some examples will be explained below.

## Example usage

For example, if I wanted to run a dictionary attack on an NTLM hash using cudahashcat, I would do this:

```bash
# cudahashcat -m 1000 /path/to/hash /path/to/dictionary
```

To break it down:
```
cudahashcat: That's the name of the package

-m 1000: Tells cudahashcat what kind of hash we are cracking. So for example I used 1000 on this attack because it was NTLM. Example hash types will be given in the end of this entry.

/path/to/hash: Giving the location of our hash on disk. This can be a hash obtained from a SAM dump (with bkhive or samdump2 maybe, both in our repositories) or a standalone hash in a file.

/path/to/dictionary: Is the location of our dictionary file. Can be obtained on various sources around the internet. An archstrike-wordlists package might be in the works soon. 
```

Here you can get [bkhive](https://archstrike.org/packages/bkhive) and [samdump2](https://archstrike.org/packages/samdump2).

Another example, if I wanted to run a mask attack on an NTLM hash, I would do this:

```bash
# cudahashcat -m 1000 -a 3 /path/to/hash /path/to/mask
```

The command didn't change much.

```
-a 3: Tells hashcat we want to do a mask attack

/path/to/mask: Is the location of our mask file. (more info on hashcat.net about attack types)
```

## Example hash types

```
0 = MD5
 10 = md5($pass.$salt)
 20 = md5($salt.$pass)
 30 = md5(unicode($pass).$salt)
 40 = md5($salt.unicode($pass))
 50 = HMAC-MD5 (key = $pass)
 60 = HMAC-MD5 (key = $salt)
 100 = SHA1
 110 = sha1($pass.$salt)
 120 = sha1($salt.$pass)
 130 = sha1(unicode($pass).$salt)
 140 = sha1($salt.unicode($pass))
 150 = HMAC-SHA1 (key = $pass)
 160 = HMAC-SHA1 (key = $salt)
 200 = MySQL
 300 = MySQL4.1/MySQL5
 400 = phpass, MD5(WordPress), MD5(phpBB3)
 500 = md5crypt, MD5(Unix), FreeBSD MD5, Cisco-IOS MD5
 800 = SHA-1(Django)
 900 = MD4
 1000 = NTLM
 1100 = Domain Cached Credentials, mscash
 1400 = SHA256
 1410 = sha256($pass.$salt)
 1420 = sha256($salt.$pass)
 1430 = sha256(unicode($pass).$salt)
 1440 = sha256($salt.unicode($pass))
 1450 = HMAC-SHA256 (key = $pass)
 1460 = HMAC-SHA256 (key = $salt)
 1600 = md5apr1, MD5(APR), Apache MD5
 1700 = SHA512
 1710 = sha512($pass.$salt)
 1720 = sha512($salt.$pass)
 1730 = sha512(unicode($pass).$salt)
 1740 = sha512($salt.unicode($pass))
 1750 = HMAC-SHA512 (key = $pass)
 1760 = HMAC-SHA512 (key = $salt)
 1800 = SHA-512(Unix)
 2400 = Cisco-PIX MD5
 2500 = WPA/WPA2
 2600 = Double MD5
 3200 = bcrypt, Blowfish(OpenBSD)
 3300 = MD5(Sun)
 3500 = md5(md5(md5($pass)))
 3610 = md5(md5($salt).$pass)
 3710 = md5($salt.md5($pass))
 3720 = md5($pass.md5($salt))
 3810 = md5($salt.$pass.$salt)
 3910 = md5(md5($pass).md5($salt))
 4010 = md5($salt.md5($salt.$pass))
 4110 = md5($salt.md5($pass.$salt))
 4210 = md5($username.0.$pass)
 4300 = md5(strtoupper(md5($pass)))
 4400 = md5(sha1($pass))
 4500 = sha1(sha1($pass))
 4600 = sha1(sha1(sha1($pass)))
 4700 = sha1(md5($pass))
 4800 = MD5(Chap)
 5000 = SHA-3(Keccak)
 5100 = Half MD5
 5200 = Password Safe SHA-256
 5300 = IKE-PSK MD5
 5400 = IKE-PSK SHA1
 5500 = NetNTLMv1-VANILLA / NetNTLMv1-ESS
 5600 = NetNTLMv2
 5700 = Cisco-IOS SHA256
 5800 = Samsung Android Password/PIN
 6300 = AIX {smd5}
 6400 = AIX {ssha256}
 6500 = AIX {ssha512}
 6700 = AIX {ssha1}
 6900 = GOST, GOST R 34.11-94
 7000 = Fortigate (FortiOS)
 7100 = OS X v10.8
 7200 = GRUB 2
 7300 = IPMI2 RAKP HMAC-SHA1
 7400 = sha256crypt, SHA256(Unix)
 9999 = Plaintext
```
