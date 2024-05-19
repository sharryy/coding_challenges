folders=$(ls -d */ | cut -f1 -d'/')

composer --version >/dev/null 2>&1

if [ $? -eq 0 ]; then
  printf "\nComposer is installed\n"
else
  printf "\nComposer is not installed\n"
  printf "Please install composer before running this script\n"
  exit 1
fi

for folder in $folders; do
  printf "\nInstalling dependencies for $folder\n"
  cd $folder
  composer install
  cd ..
done

printf "\nAll dependencies installed\n"

for folder in $folders; do
  printf "\nRunning tests for $folder\n"
  cd $folder
  ./vendor/bin/pest
  cd ..
done