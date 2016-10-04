# WAS
##*What is the WAS*
WAS stands for WebApplicationSystem und is a framework designed to strictly separate multiple things.
It is split up into 4 levels:
1. socket-level.

    * The socket-level contains one socket which acts like the "BIOS" of this system. It coordinates the other
    levels and provides functionality for them. It should only contain basic functionality, like for example
    a way to debug stuff.
 
2. package-level.

    * This Level is meant to be used for logic. For example: If you wanted to use a database inside this puppy,
    you would create a db.package, which provides either functions or classes for the mods and the theme.

    * The package-level contains folders written as *<package_name>.package*. You could add more information to the name,
    like *<package_name>.<package_version>.<...>.package* as long as it contains "*.package".
    
    * Every package has to meet certain requirements. These are collected in the "packages/requirements/"-folder.
    But you can change the location of the requirements in the settings.ini file, which will be explained later.
    
3. mod-level.

    * This level is meant to be used for (mostly) independent applications. They can still contain requirements,
    like "foo.package", but the should be independent to the theme
    
    * The mod-level contains the mods, that will be dynamically loaded into an call of the index.php of the WAS.
    Everything else is the same as with the package-level, except that this folders will have to end at "*.mod".
    
4. theme-level

    * The theme level is different than the mod- or package-level. It contains your actual theme. While the requirements
    may alter from socket to socket, i will later explain it based on the example socket, this WAS comes with.

## Example run through with the main.socket

*Since i am not providing an example-theme, this might be a bit boring. However, if you are willing to see a example
in a different language (german), the [wasVPM](https://github.com/ThorbenKuck/wasVPM) might be something for you*

We start with opening the browser. This calls the index.php of the WAS.
There is not much going on here. It start by starting the session and output buffering.
It then simply requires the Main.class.php file, which includes the "Main"-Class
(i could not come up with a better name ... sorry). This class might, in the eye of someone, who only programs
in an oop-manner, be very ugly, since it only contains static methods. However this comes very handy at certain points!

We then call the <code>loadSocket()</code>-method. This will load the <code>main.socket/index.php</code> file.
If you wanted to change the name, of the socket, you would have to change it inside this Class or by using
the <code>Main::changeSocketName()</code> method. Afterwards it calls the <code>\Main\main()</code> function.

Inside this function we find the basic scheme of the WAS. It looks like this:

<code>init ();<br>
load_all_packages ();<br>
load_mods ();<br>
load_theme ();<br>
eoe ();
</code>

So inside the Socket it works like this:
<code>load_packages -> load_mods -> load_theme</code>

From this you can already take, how they depend on each other. The "packages" are stand-alone's, while the "mods"
are able to use all "packages" and the "theme" can use all "packages" and "mods".

For the packages and mods there are 2 hooks (requirements) for the socket (in this example).
1. The config.json.

    The config  contains some information's. For example, wheter or not the mod / package is active, or if it
    only is usable by administrator or what else (package or mod) is required for this to be implemented.
    
    Let's say, for example, we have mod A and package B. Mod a has inside his config stated that he needs package C.
    Wile package B would be used and included, the mod A would not.
    
2. The index.php

    This is a hook, which should resolve all mod-/package-internal dependencies, so that the socket (or 
    anything for that matter) can simply include the index.php and be up and running.
    
    Example: The Mod A uses a file called 'uberfile.php'. Inside the index.php he would than state:
    <code>require dirname(\_\_FILE__) . '/uberfile.php';</code>.
    
    Please note, that you can never be certain, from where this is called. So you should not be using relative
    paths!

But back to the scheme!
First of there is init(). Init creates internal dependencies of the socket. For example the structure of the
'System'-session, which is used for path-information's, debug, so on and so forth.

Second of, it calls the load_all_packages() function. This algorithm is a bit longer. If you want to look into it
you can, by going into <code>socket/main.socket/main.packages.functions.php</code>, but i am not going to explain
it in detail here. The same counts for the load_mods() function.

As we come to the load_theme() function, it gets a bit more interesting. You can install several themes
simultaneously, but only one can be active. This can be changed via the settings.ini file.

The current algorithm is trying to include 4 parts. The theme-config, the header, the body and the navigation.
Don't panic! This is not complicated. It basically looks for 4 files inside the active-theme-folder:
1. config.php
2. header.php
3. index.php (as the body ... didn't i already say, that i am bad with word?)
4. nav.php

However! You could tweak the system a bit. By default, it looks for a bit more like this:

1. config.php
2. header
    1. header_logd_in.php if you are logdin or header_not_logd_in.php if you aren't
    2. header.php if the needed file does not exist.
3. index
    1. index_logd_in.php if you are logdin or index_not_logd_in.php if you aren't
    2. index.php if the needed file does not exist.
4. nav
    1. nav_logd_in.php if you are logdin or nav_not_logd_in.php if you aren't
    2. nav.php if the needed file does not exist.
    
So there is no need to create a complex system for login, it already is on.

Now, let's assume we just take this empty project and start it. We don't have anything else, than the socket.
If we now run it, in (let's say) an apache2-server, it wouldn't work. If we now take a look at the 
eoe()-function, we will know more about debugging. 

The first thing we should look into is the settings.ini file. By default it looks something like this:

<code>[modes]</br>
debug_mode = on</br>
dev_mode = on</br></br>
[mods]</br>
mod_usage = on</br></br>
[debug_window]</br>
open_debug_window = yes</br>
admin_only_debug_window = no</br></br>
[info_window]</br>
open_info_window = yes</br>
admin_only_info_window = no</br></br>
[debug_stacktrace]</br>
empty_debug_stacktrace_after_execution = no</br></br>
[packages]</br>
package_usage = on</br>
package_requirement_folder = packages/requirements/</br>
package_folder = packages/</br></br>
[theme]</br>
active_theme = example</br></br>
[test]</br>
use_test_file = true
</code>

The debug-mode enables the debug and info windows. If both of these are set to <code>open_*_window = yes</code>
(and optional to <code>admin_only_info_window = no</code>), the eoe function from the socket will kick in and
open a window, with manual-debug-stacktrace and all set info's / settings.

If we turn the dev_mode on, we will see php warnings/errors and everything else the compiler spits out.

You can also turn on / off multiple other things, like mods / packages, set the requirement-folders to different
locations an so on.

##Motivation
I created a website for controlling monitors via the internet. The problem was, that after i tested an let
it run for a couple of weeks i wanted to change the behavior of certain parts. This took me so long, that
i rather started from scratch. I looked for a framework that provided an easy adjustable while stile strong
rule-set.

I decided to create this system after i had to program a gigantic project with multiple colleges at the university. 
It was a pain in the ass to maintain anything that does not follow a strict style-rule (like MVP). So, after
i came up with an system for java, i decided to do the same for php.

The monitor-automation-project ca be found here: [wasVPM](https://github.com/ThorbenKuck/wasVPM) and is still
being developed.

I want the WAS to become an community based framework, that you can lean towards for gigantic and/or very complicated
projects, while still having all control over it, what how it does and how it does that. Just like an operating system.

##Why should i use the WAS?
This system is designed to strictly split up dependency's. It can be a powerful tool for creating complicated
and / or gigantic projects, since you can enable / disable / add / remove /debug certain mods / packages "on the fly".
Similar to the MVP or MCP pattern, you will, at the long run, benefit from splitting dependency's, whether you
use this framework or anything else.

If you are now thinking "*this just makes things way more complicated*" you are partly right.
Setting this up and get it running after a fresh start __might__ be a bit more time intensive. But from there on
it just becomes way more easy to maintain and expand the project.

**Do not confuse this with a dependency injection (DI) framework**

The WAS will take care of some dependency's and analyse requirements (dependency's) for mods and packages and include them.
However, this system is mostly optimised for asynchronous projects. I guess you could write a mod or socket,
that provides you with a way for synchronous projects, but i am focusing at the asynchronous part here.

If you are willing, you could change this to anything you want. Like, for example, a DI-framework for a synchronous
project.
##Current version

__(ALPHA) v.0.1__

## Latest changelog:

####legend
\+ = added following

\* = changed following

\- = removed following

__(ALPHA) v.0.1__
* **structure**
    * \+ logs-folder
    * \+ mods-folder
        * \+ requirements-folder
            * \+ config.json as requirement for all mods
            * \+ index.php as requirement for all mods
    * \+ packages-folder
        * \+ requirements-folder
            * \+ config.json as requirement for all mods
            * \+ index.php as requirement for all mods
    * \+ socket-folder
        * \+ main.socket-folder (many files inside)
* **main.socket**
    * \+ (Main)class for coordinate functionality with the socket
    * \+ logs compatibility
    * \+ package-support and analytics
    * \+ mod-support and analytics
    * \+ theme-support
    * \+ settings-support
    * \+ info-support
    * \+ debug-support
    * \+ user/login-pseudo-support (just some basic login-features, which can be expanded by packages)


## For whom this is

Anyone who want's to create and maintain large and/or complicated projects

## For whom this isn't

Someone who just want's to create a simple website. In this case, it will be way more complicated, than it
has to be!

##Installation ...

Since this is (nearly) plain php, you can just clone this project and run it in any web-server (like apache2).
However, i have to admit, that i never tested it inside of an Windows-environment..

### ... of mods

Simply put the *.mod folder inside your <code>mods/</code> folder. Done. (Well, maybe you will have to install
another requirement for that mod or change it to active. For that look into the config.json)

If you are looking to create a mod, simply create a *.mod folder in <code>mods/</code>, so that is looks like
this: <code>path/to/project/mods/example.mod</code> and run the project (<code>path/to/project/index.php</code>) once. It will include
all dependency's for you (like the index.php and config.json).

Afterwards you can upload it to anything and provide the mod for anyone who want's to use it.

### ... of packages

Simply put the *.package folder inside your <code>packages/</code> folder. Done. (Well, maybe you will have to install
another requirement for that package or change it to active. For that look into the config.json)

If you are looking to create a package, simply create a *.package folder in <code>package/</code>, so that is looks like
this: <code>path/to/project/packages/example.package</code> and run the project (<code>path/to/project/index.php</code>) once. It will include
all dependency's for you (like the index.php and config.json).

Afterwards you can upload it to anything and provide the mod for anyone who want's to use it.

### ... of sockets

__WARNING!__ The socket has to be installed with care. The socket is the Heard of the whole System!
If, for example, a socket is installed, wich does not provide certain functions, which you packages or mods need
or just has different function-names or something, it might take you a while to figure out the exact error! 
I recommend starting with a Socket and building everything else around it, as long as socket-dependency's 
aren't implemented.

Simply put the *.socket folder inside your <code>socket/</code> folder (<code>path/to/project/socket/</code>). Done.

### ... of themes

Again, simply put the folder of the theme inside the themes-folder (<code>path/to/project/themes/</code>). If
you want to use it, change the name of <code>active_theme</code> in the <code>settings.ini</code> the folder-name.

To create a theme, you should look into the load_theme()-algorithm, or at [wasVPM](https://github.com/ThorbenKuck/wasVPM)
for an example. I will, sometime in the future provide an example theme with explanation.

##Contributors

If you want to dive into the project, just check out the WIKI for coding-standards. If there is anything
unclear, i am sorry. This is one of my first github-projects.

##TODO

* custom_error_handler
* requirements (dependency) for the socket
    * This is an idea and i am not sure if this is going to become a thing!
* package-/mod-requirement for a certain mod
* Enhance the debug of the main.socket
* Documentation (as always... sorry)

## Known bugs
* Because the WAS is based on Sessions, if you have your session-time-settings to low, you might encounter
problems along the line

## License
gpl-3.0