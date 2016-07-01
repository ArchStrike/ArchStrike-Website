## Obtaining Info About Targets with Recon-NG

This tutorial will go through some ways of obtaining info on a target with Recon-NG tool available in ArchStrike.

First, let's download Recon-NG (obviously you don't need to do this if you have it already installer)

```
# pacman -S recon-ng-git
```

Let's start it up.

```
# recon-ng
```

Now you should see a menu similar to this:

```
[75] Recon modules
[7]  Reporting modules
[2]  Import modules
[2]  Exploitation modules
[2]  Discovery modules

[recon-ng][default] >
```
Now let's get some help.

```
[recon-ng][default] > help

Commands (type [help|?] <topic>):
---------------------------------
add             Adds records to the database
back            Exits the current context
delete          Deletes records from the database
exit            Exits the framework
help            Displays this menu
keys            Manages framework API keys
load            Loads specified module
pdb             Starts a Python Debugger session
query           Queries the database
record          Records commands to a resource file
reload          Reloads all modules
resource        Executes commands from a resource file
search          Searches available modules
set             Sets module options
shell           Executes shell commands
show            Shows various framework items
snapshots       Manages workspace snapshots
spool           Spools output to a file
unset           Unsets module options
use             Loads specified module
workspaces      Manages workspaces

```
Let's see what modules he have.

```
[recon-ng][default] > show
Shows various framework items

Usage: show [banner|companies|contacts|credentials|dashboard|domains|hosts|keys|leaks|locations|modules|netblocks|options|ports|profiles|pushpins|repositories|schema|vulnerabilities|workspaces]

[recon-ng][default] > show modules

  Discovery
  ---------
  discovery/info_disclosure/cache_snoop
  discovery/info_disclosure/interesting_files


```

There is a lot more but if I were to put it all this would get very long. Instead you can inspect the output on your terminal better.

```
recon/hosts-hosts/ssltools
recon/domains-hosts/hackertarget
```

These modules looks interesting, let's see what they do.

```
[recon-ng][default] > use recon/hosts-hosts/ssltools
[recon-ng][default][ssltools] > show options

  Name      Current Value  Required  Description
  --------  -------------  --------  -----------
  RESTRICT  True           yes       restrict added hosts to current domains
  SOURCE    default        yes       source of input (see 'show info' for details)

```

When you want to learn more about a module, you can use:

```
[recon-ng][default][ssltools] > show info
```

As you see, we need to set our source to our target. I'll use `google.com` in my example.

```
[recon-ng][default][ssltools] > set source google.com
SOURCE => google.com
```

Now I do this to kick off the module:

```
[recon-ng][default][ssltools] > run
```

Pretty easy. Now it will give me output regarding my target. You can try and experiment on your own.

Let's see the other module.

```
[recon-ng][default][ssltools] > back
[recon-ng][default] > use recon/domains-hosts/hackertarget
[recon-ng][default][hackertarget] > show options

  Name    Current Value  Required  Description
  ------  -------------  --------  -----------
  SOURCE  default        yes       source of input (see 'show info' for details)
```

Let's set our source again and run the module.

```
[recon-ng][default][hackertarget] > set source google.com
SOURCE => google.com
[recon-ng][default][hackertarget] > run
```

Feel free to explore all the modules Recon-NG has to offer, but be responsible in your actions. 

**Disclaimer:** Packages in ArchStrike are licensed by their upstream author and under no case ArchStrike or the script author can be held responsible for your actions.
