# Enumerating Directories with DirB

This tutorial will be about using the `dirb` tool to enumerate files and directories on a webserver.

First we'd want the tool installed.

```
# pacman -S dirb
```

Obviously you don't need to do this if you have it installed.

Now we can run the tool to see the help page.

```
# dirb

-----------------
DIRB v2.22    
By The Dark Raver
-----------------

./dirb <url_base> [<wordlist_file(s)>] [options]

========================= NOTES =========================
 <url_base> : Base URL to scan. (Use -resume for session resuming)
 <wordlist_file(s)> : List of wordfiles. (wordfile1,wordfile2,wordfile3...)

======================== HOTKEYS ========================
 'n' -> Go to next directory.
 'q' -> Stop scan. (Saving state for resume)
 'r' -> Remaining scan stats.

======================== OPTIONS ========================
 -a <agent_string> : Specify your custom USER_AGENT.
 -c <cookie_string> : Set a cookie for the HTTP request.
 -f : Fine tunning of NOT_FOUND (404) detection.
 -H <header_string> : Add a custom header to the HTTP request.
 -i : Use case-insensitive search.
 -l : Print "Location" header when found.
 -N <nf_code>: Ignore responses with this HTTP code.
 -o <output_file> : Save output to disk.
 -p <proxy[:port]> : Use this proxy. (Default port is 1080)
 -P <proxy_username:proxy_password> : Proxy Authentication.
 -r : Don't search recursively.
 -R : Interactive recursion. (Asks for each directory)
 -S : Silent Mode. Don't show tested words. (For dumb terminals)
 -t : Don't force an ending '/' on URLs.
 -u <username:password> : HTTP Authentication.
 -v : Show also NOT_FOUND pages.
 -w : Don't stop on WARNING messages.
 -X <extensions> / -x <exts_file> : Append each word with this extensions.
 -z <milisecs> : Add a miliseconds delay to not cause excessive Flood.

======================== EXAMPLES =======================
 ./dirb http://url/directory/ (Simple Test)
 ./dirb http://url/ -X .html (Test files with '.html' extension)
 ./dirb http://url/ /usr/share/dirb/wordlists/vulns/apache.txt (Test with apache.txt wordlist)
 ./dirb https://secure_url/ (Simple Test with SSL)
```

The help page explains a lot of the modes and options we have with this tool.

Let's run a basic mode first. I'm going to run it against a vulnerable virtual machine I have.

```
# dirb http://192.168.1.6/

-----------------
DIRB v2.22    
By The Dark Raver
-----------------

START_TIME: Tue Jul 12 16:04:08 2016
URL_BASE: http://192.168.1.6/
WORDLIST_FILES: /usr/share/dirb/wordlists/common.txt

-----------------

GENERATED WORDS: 4612

---- Scanning URL: http://192.168.1.6/ ----
==> DIRECTORY: http://192.168.1.6/0/
==> DIRECTORY: http://192.168.1.6/admin/
+ http://192.168.1.6/atom (CODE:301|SIZE:0)
==> DIRECTORY: http://192.168.1.6/audio/
==> DIRECTORY: http://192.168.1.6/blog/
==> DIRECTORY: http://192.168.1.6/css/
+ http://192.168.1.6/dashboard (CODE:302|SIZE:0)
+ http://192.168.1.6/favicon.ico (CODE:200|SIZE:0)
==> DIRECTORY: http://192.168.1.6/feed/
==> DIRECTORY: http://192.168.1.6/image/
==> DIRECTORY: http://192.168.1.6/Image/
==> DIRECTORY: http://192.168.1.6/images/
+ http://192.168.1.6/index.html (CODE:200|SIZE:1077)
+ http://192.168.1.6/index.php (CODE:301|SIZE:0)
+ http://192.168.1.6/intro (CODE:200|SIZE:516314)
==> DIRECTORY: http://192.168.1.6/js/
+ http://192.168.1.6/license (CODE:200|SIZE:309)
+ http://192.168.1.6/login (CODE:302|SIZE:0)
+ http://192.168.1.6/page1 (CODE:301|SIZE:0)
+ http://192.168.1.6/phpmyadmin (CODE:403|SIZE:94)
+ http://192.168.1.6/rdf (CODE:301|SIZE:0)       
+ http://192.168.1.6/readme (CODE:200|SIZE:7355)
+ http://192.168.1.6/robots (CODE:200|SIZE:41)
+ http://192.168.1.6/robots.txt (CODE:200|SIZE:41)
+ http://192.168.1.6/rss (CODE:301|SIZE:0)
+ http://192.168.1.6/rss2 (CODE:301|SIZE:0)
+ http://192.168.1.6/sitemap (CODE:200|SIZE:0)
+ http://192.168.1.6/sitemap.xml (CODE:200|SIZE:0)
==> DIRECTORY: http://192.168.1.6/video/
==> DIRECTORY: http://192.168.1.6/wp-admin/
+ http://192.168.1.6/wp-config (CODE:200|SIZE:0)
==> DIRECTORY: http://192.168.1.6/wp-content/
+ http://192.168.1.6/wp-cron (CODE:200|SIZE:0)
==> DIRECTORY: http://192.168.1.6/wp-includes/
+ http://192.168.1.6/wp-links-opml (CODE:200|SIZE:227)
+ http://192.168.1.6/wp-load (CODE:200|SIZE:0)
+ http://192.168.1.6/wp-login (CODE:200|SIZE:2599)
+ http://192.168.1.6/wp-mail (CODE:500|SIZE:3064)
+ http://192.168.1.6/wp-settings (CODE:500|SIZE:0)
+ http://192.168.1.6/wp-signup (CODE:302|SIZE:0)
+ http://192.168.1.6/xmlrpc (CODE:405|SIZE:42)
+ http://192.168.1.6/xmlrpc.php (CODE:405|SIZE:42)

---- Entering directory: http://192.168.1.6/0/ ----
+ http://192.168.1.6/0/atom (CODE:301|SIZE:0)

```

Depending on your connection with the target this might take a while. I got the above output from running this. I didn't put the whole thing because it was too long, but it's an easy idea to understand.

DirB also goes into the directories it finds and runs the same scans in those as well, so it is recursive(As seen above last 2 lines).

Let's look at some interesting options.

```
-a : Will allow you to change your User Agent.
-f : Will fine tune the HTTP 404 detection.
-z : Will allow you to add a miliseconds delay to not cause flood
-X : Will allow you to search for specific extensions (html, pdf etc.)
-R : Interactive recursion

```

With these options it's possible to carry out more targeted attacks. For example if I wanted to see just .php files on the server I'd run this:

```
# dirb http://192.168.1.6 -X .php

-----------------
DIRB v2.22    
By The Dark Raver
-----------------

START_TIME: Tue Jul 12 16:14:16 2016
URL_BASE: http://192.168.1.6/
WORDLIST_FILES: /usr/share/dirb/wordlists/common.txt
EXTENSIONS_LIST: (.php) | (.php) [NUM = 1]

-----------------

GENERATED WORDS: 4612                                                          

---- Scanning URL: http://192.168.1.6/ ----
+ http://192.168.1.6/index.php (CODE:301|SIZE:0)
+ http://192.168.1.6/wp-app.php (CODE:403|SIZE:0)
+ http://192.168.1.6/wp-atom.php (CODE:301|SIZE:0)
+ http://192.168.1.6/wp-commentsrss2.php (CODE:301|SIZE:0)
+ http://192.168.1.6/wp-config.php (CODE:200|SIZE:0)
+ http://192.168.1.6/wp-cron.php (CODE:200|SIZE:0)
+ http://192.168.1.6/wp-feed.php (CODE:301|SIZE:0)
+ http://192.168.1.6/wp-links-opml.php (CODE:200|SIZE:227)
+ http://192.168.1.6/wp-load.php (CODE:200|SIZE:0)
+ http://192.168.1.6/wp-login.php (CODE:200|SIZE:2657)
+ http://192.168.1.6/wp-mail.php (CODE:500|SIZE:3064)
+ http://192.168.1.6/wp-rdf.php (CODE:301|SIZE:0)
+ http://192.168.1.6/wp-register.php (CODE:301|SIZE:0)
+ http://192.168.1.6/wp-rss.php (CODE:301|SIZE:0)
+ http://192.168.1.6/wp-rss2.php (CODE:301|SIZE:0)
+ http://192.168.1.6/wp-settings.php (CODE:500|SIZE:0)
+ http://192.168.1.6/wp-signup.php (CODE:302|SIZE:0)
+ http://192.168.1.6/xmlrpc.php (CODE:405|SIZE:42)

-----------------
END_TIME: Tue Jul 12 16:16:19 2016
DOWNLOADED: 4612 - FOUND: 18

```

This is all to be covered in this tutorial. Feel free to explore this amazing tool by yourself, but be responsible.

**Disclaimer:** Packages in ArchStrike are licensed by their upstream author and under no case ArchStrike or the script author can be held responsible for your actions. 
