# type2.com


this is MERGED with a wallflower build version of the "new" dynamic site, plus a wget mirror of parts that can't be built through wallflower.



# type2.com

this was a ~12/2022 capture of a static version of the perl website, generated by wallflower

it's imperfect, particularly since the dynamic parts aren't going to work.


this is populated from ../gitlab/website-type2/build.sh
at least the wallflower branch


----




This is dual deployed to vercel and render:

https://vercel.com/type2com/website  -->

dev branch --> https://website-tau-jet.vercel.app/

master branch --> https://www.type2.com/

Dynamic branch envs use this pattern:

https://website-git-dev-type2com.vercel.app/
https://website-git-master-type2com.vercel.app/



https://dashboard.render.com/static/srv-cebb9farrk0bbte9vqm0

master branch --> https://type2.onrender.com/

12/19/23 render is broken internally - wont clone, so no updates since 12/12/22

----




this is a static copy of the old www.type2.com website - before the https/perl version.

this was assembled ~12/12/2022 from the filesystem of purple.type2.com and _should_ reflect how the webserver served it, complete with homedirs etc

base from home/www/type2.com/public_html/

has 6 symlinks:

./bartnik
./rvanness
./rescue
./archives
./m-codes
./molenari

so we can work around these as needed


