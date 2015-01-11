###########################################################
######## copy vendor into app/Resources/public ############
###########################################################

rm -Rf app/Resources/public/mikegerwitz/easejs
mkdir -p app/Resources/public/mikegerwitz/easejs
cp -Rf vendor/mikegerwitz/easejs/* app/Resources/public/mikegerwitz/easejs

rm -Rf app/Resources/public/twbs/bootstrap
mkdir -p app/Resources/public/twbs/bootstrap
cp -Rf vendor/twbs/bootstrap/dist/* app/Resources/public/twbs/bootstrap

rm -Rf app/Resources/public/jquery/jquery
mkdir -p app/Resources/public/jquery/jquery/
cp -Rf vendor/jquery/jquery/* app/Resources/public/jquery/jquery