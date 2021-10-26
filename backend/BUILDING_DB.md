# Building the Oracle DB

This project is based on a remote OracleDB hosted by OVPR.

This document will describe the process we used to build a version of OracleDatabase 18 Express Edition (which is all we had access to at the time.)


## Setup / Retrieving Build Files

First thing, we need to download the files to build.  Oracle has a [repository for their docker images](https://github.com/oracle/docker-images).

```
cd vendor
git clone git@github.com:oracle/docker-images.git
cd docker-images
```

## Building a version

Typically, you would need to now retrieve the binary for the version of Oracle Database you want to run.  I didn't have these at the time, and thankfully version 18 of the Express Edition is free and downloads automatically.  If not, the binary should be available on an install page: [https://www.oracle.com/database/technologies/xe18c-downloads.html](https://www.oracle.com/database/technologies/xe18c-downloads.html).

If you end up needing to use a binary, just plop it in to the `vendor/docker-images/OracleDatabase/SingleInstance/dockerfiles/<your version number>` directory and use that version number in the command later.

Now, lets build.  Navigate to the dockerfiles directory.

```
cd OracleDatabase/dockerfiles
```

You should have a `buildContainerImage.sh` file, and a bunch of version number folders.  Just make sure the buildContainerImage.sh file is executable.

Now, lets build the oracle DB image.  If you are not using the express hosted version, change `18.4.0` with your version folder

```
./buildContainerImage.sh -v 18.4.0 -x
```

## Running

Now that your DB image should exist, it's time to run.

```
docker-compose-up
```

This should start a lengthy process of starting this image for the first time.  You will see a message in the logs when the image is ready and provisioned for the first time.

```
#########################
DATABASE IS READY TO USE!
#########################
```

By default, we are saving volume data in this project directory (`./mounts/oradata`), since this image takes forever to start up for the first time, I recommend not wiping this out if you can.