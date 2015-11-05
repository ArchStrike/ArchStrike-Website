# Repositories

## archstrike

The [archstrike](https://github.com/ArchStrike/ArchStrike/tree/master/archstrike) repository is our official, stable repository for ArchStrike packages. Packages in this repo have been reviewed, tested, are kept up to date, and generally meet a minimum standard of care

## archstrike-testing

The [archstrike-testing](https://github.com/ArchStrike/ArchStrike/tree/master/archstrike-testing) repository is the complete set of packages we migrated over from the (now defunct) ArchAssault repo. These packages haven't been cleaned up or fully tested yet, and therefore don't meet the minimum standard of care we're giving packages in our main repo. The majority of the packages in this repo install and run without any issues, but there will more than likely be some with issues too.

We encourage everyone who wants access to the full set of packages to use both repos, with the understanding that we make no guarantees that everything will work perfectly. If bugs in packages are found, feel free to [create an issue](https://github.com/ArchStrike/ArchStrike/issues) letting us know so one of us can take a look at fixing it.

**NOTE**: The `archstrike-testing` repository _extends_ the `archstrike` repository, and shouldn't be used on its own.

## Package Cleanup Requests

We're doing our best to review and fix issues with packages so they can be moved from `archstrike-testing` to `archstrike`, but there are quite a few packages and some might take a bit of time before we get to them. If you need a specific tool to be stable and want us to give it a higher priority, [create an issue](https://github.com/ArchStrike/ArchStrike/issues) and let us know! Testing and fixes outlined in the issue and/or pull requests will speed up the process even more.
