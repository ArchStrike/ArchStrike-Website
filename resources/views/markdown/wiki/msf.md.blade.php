# How to setup PostgreSQL Database for Metasploit Framework

## Getting postgresql running

In order to configure the database for Metasploit Framework, you'll need to create a postgresql database.

If it is the first time you are running postgres, make sure to initialize the database cluster first.

```bash
sudo -i -u postgres
```

to become the postgres user, and then run:

```bash
initdb --locale $LANG -E UTF8 -D '/var/lib/postgres/data'
```

as the postgres user to achieve it.

Then switch to the root user and start and enable the postgresql service with:

```bash
systemctl start postgresql
systemctl enable postgresql
```

Now that postgresql is running, it is time to create our database for MSF.

## Creating your MSF database on postgresql

Run the following commands as the postgres user to create your database

```bash
createuser msfdbuser -P -S -R -D
createdb -O msfdbuser msfdb
exit
```

## Create and configure database.yml for MSF to use

```conf
production:
adapter: "postgresql"
database: "msfdb"
username: "msfdbuser"
password: "CHANGEME"
port: 5432
host: "localhost"
pool: 256
timeout: 5
```

Save this file to `/usr/share/metasploit/database.yml` and make sure you change the CHANGEME password to the one you specified while creating the database.

Now set the `MSF_DATABASE_CONFIG` environment variable so that it points to your `database.yml`

```bash
sudo echo 'export MSF_DATABASE_CONFIG=/usr/share/metasploit/database.yml' > /etc/profile.d/msf.sh
chmod 755 /etc/profile.d/msf.sh
```

Then source `msf.sh` or log out and back in to your system.

```bash
source /etc/profile.d/msf.sh
```

And finally start msfconsole. It will detect if `database.yml` exists and connect to the postgresql database.

```bash
msfconsole
```
