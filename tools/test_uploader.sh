if ! test -f dynamic/videos/BigBuckBunnyTest.mp4; then
  echo "Downloading Big Buck Bunny MP4 from Blender servers"
  wget https://download.blender.org/peach/bigbuckbunny_movies/BigBuckBunny_320x180.mp4 -O dynamic/videos/BigBuckBunnyTest.mp4
else
  echo "Big Buck Bunny has already been downloaded. If running from production, please remove this file."
fi

php private/scripts/processingworker.php "BigBuckBunnyTest" "dynamic/videos/BigBuckBunnyTest.mp4"