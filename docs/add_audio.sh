#!/bin/bash 
# usage: ./add_audio.sh  DIRECTORY_CONTAINING_MP3s LANGUAGE_ISO_CODE MYSQL_USERNAME MYSQL_PASSWORD MYSQL_DB DEST_BASE
# for example: ./add_audio.sh my_eng_sentences eng root tatoeba tatoeba /var/audio

if [ $# -ne 6 ]; then
    echo "Usage: add_audio.sh  DIR_W_MP3s LANG_CODE MYSQL_USER MYSQL_PWD MYSQL_DB DEST_BASE"
    echo "Example: add_audio.sh my_eng_sentences eng root tatoeba tatoeba /var/audio"
else
    MYSQL_USER=$3
    MYSQL_PASSWORD=$4
    MYSQL_DB=$5
    AUDIO_ROOT_DIR="$6/$2"
    cd $1
    for MP3 in *.mp3
        do
        SENTENCE_ID="${MP3%.mp3}";
        # we check if the sentence id is a number to avoid sql injection
        if [ $SENTENCE_ID -eq $SENTENCE_ID 2> /dev/null ]; then
            echo "Adding $SENTENCE_ID";
            echo "UPDATE sentences SET hasaudio = 'shtooka' where id = $SENTENCE_ID;" >> update_hasaudio.sql;
        else
            echo "$SENTENCE_ID does not have the form dddd.mp3; will be skipped"
        fi
    done;

    mysql -u$MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DB < update_hasaudio.sql;

    MP3DIR=$AUDIO_ROOT_DIR
    if [ ! -d  "$MP3DIR" ];then
        mkdir "$MP3DIR"
    fi

    mv *.mp3 "$MP3DIR"
fi