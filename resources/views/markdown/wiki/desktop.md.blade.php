## Setting up Desktop configs for ArchStrike

### OpenBox

The configuration files go to `/usr/share/archstrike-openbox-config`

To change your openbox configuration, please run the following commands.

1) Remove the current openbox configuration files (not to be done if it's your first openbox install)

```bash
# lets backup first
mkdir -p ~/.config/backup
cp -a ~/.config/obmenu-generator ~/.config/openbox ~/.config/tint2 ~/.config/volumeicon ~/.config/backup
rm -rf ~/.config/obmenu-generator ~/.config/openbox ~/.config/tint2 ~/.config/volumeicon
```

You can return to your old configurations by copying the contents of the backup directory to ~/.config

2) Copy files

`cp -a /usr/share/archstrike-openbox-config/etc/* ~/.config/`
                     
3) Add openbox to your .xinitrc

```bash
rm .xinitrc
echo 'exec openbox' > .xinitrc
```

4) Restart X or reboot
If you are using a display manager you'll need to change your default desktop environment/window manager to openbox

## i3

The configuration files go to /usr/share/archstrike-i3-config

To change your i3 configuration, please run the following commands.

1) Remove the current i3 configuration files (not to be done if it's your first i3 install)

```bash
# lets backup first
mkdir -p ~/.config/backup
cp -a ~/.config/i3 ~/.config/i3status ~/.config/gtk-3.0 ~/.config/backup
rm -rf ~/.config/i3 ~/.config/i3status ~/.config/gtk-3.0
```

You can return to your old configurations by copying the contents of the backup directory to ~/.config

2) Copy files

`cp -a /usr/share/archstrike-i3-config/config/* ~/.config/`
  
3) Add i3 to your .xinitrc

```bash
rm .xinitrc
echo 'exec i3' > .xinitrc
```

4) Restart X or reboot
If you are using a display manager you'll need to change your default desktop environment/window manager to i3

## XFCE

The configuration files go to `/usr/share/archstrike-xfce-config`

To change your xfce configuration, please run the following commands.

1) Remove the current xfce configuration files (not to be done if it's your first xfce install)

```bash
# lets backup first
mkdir -p ~/.config/backup
cp -a ~/.config/desktop ~/.config/xfconf ~/.config/backup
rm -rf ~/.config/desktop ~/.config/xfconf
```

You can return to your old configurations by copying the contents of the backup directory to ~/.config

2) Copy files

```bash
cp -a /usr/share/archstrike-xfce-config/config/xfce4/* ~/.config/
cp -a /usr/share/archstrike-xfce-config/icons/* /usr/share/pixmaps/
cp -a /usr/share/archstrike-xfce-config/wallpapers/* /usr/share/backgrounds/xfce/
```
  
3) Add xfce4 to your .xinitrc

```bash
rm .xinitrc
echo 'exec xfce4' > .xinitrc
```

4) Restart X or reboot
If you are using a display manager you'll need to change your default desktop environment/window manager to xfce 


