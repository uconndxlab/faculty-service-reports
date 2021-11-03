# Installing PHP with OCI for MacOS

I had some issues installing so I wanted to detail them.

I was running oracle in a container previously, and installing php to the local machine with OCI capabilities and functions requires an oracle install.

You need to install instantclient, the instantclient SDK, and make sure PECL is configured properly with Homebrew to make the installation.

## Version

At the time of writing this, HomeBrew was installing version 8 of PHP, I was running 18c Oracle Database in a container, and instantclient was v19.*.

On MacOS, I installed MacOS with homebrew:

```
brew install php
```

Then, I had to add php to the path, so it takes priority over MacOS built in PHP.

In my `.zshrc` (but can easily be .bashrc or others depending on your terminal):

```
export PATH="/usr/local/opt/php/bin:$PATH"
```

Then, I downloaded both the [instantclient and instantclient SDK from Oracle](https://www.oracle.com/database/technologies/instant-client.html).

I put the files in `/usr/local/opt/instantclient` so now `/usr/local/opt/instantclient` had a bunch of `.dylib` files and `sdk` folder and file.

Next, I had to ensure the PECL directory was writable by my user, since HomeBrew installed everything thus far.

```
pecl config-get ext_dir | pbcopy
mkdir -p <your clipboard paste>
```

Lastly, install oci8 with pecl:

```
sudo pecl install oci8
```

When asked for the instantclient installation location, paste in:

```
instantclient,/usr/local/opt/instantclient
```

Next, you will need to start the web server and grant access to these dylib files.  Open MacOS Security & Privacy General tab.  In a terminal, type `php -S localhost:8000`.  As libs come up and deny permission, press "Cancel" and then allow them in the Security & Privacy tab.  You will need to do these one by one unfortunately unless you are smarter and less lazy than me. 


## Connecting with sys/sysdba

If you wish to connect as a privileged user, which is bad practice, make sure to open your php.ini file and ensure `oci8.privileged_connect = on` is there.

```
php --ini # Find info on current php.ini file being loaded
```