$(function(){
    $('.redactor-init').each(function() {
        $(this).redactor();
    });
});

/*
 emoticons : {
 viewType: 'modal',
 width: '64px',
 sets: [
 {
 name: 'Yolks',
 items: [
 {
 'name': 'are you for real',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/are_you_for_real.png",
 'shortcode' : ':are_you_for_real:'
 },
 {
 'name': 'beaten',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/beaten.png",
 'shortcode' : ':beaten:'
 },
 {
 'name': 'bouaaaaah',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/bouaaaaah.png",
 'shortcode' : ':bouaaaaah:'
 },
 {
 'name': 'brzzzzz',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/brzzzzz.png",
 'shortcode' : ':brzzzzz:'
 },
 {
 'name': 'burnt',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/burnt.png",
 'shortcode' : ':burnt:'
 },
 {
 'name': 'confident',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/confident.png",
 'shortcode' : ':confident:'
 },
 {
 'name': 'dark_mood',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/dark_mood.png",
 'shortcode' : ':dark_mood:'
 },
 {
 'name': 'disapointed',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/disapointed.png",
 'shortcode' : ':disapointed:'
 },
 {
 'name': 'eyes_on_fire',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/eyes_on_fire.png",
 'shortcode' : ':eyes_on_fire:'
 },
 {
 'name': 'faill',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/faill.png",
 'shortcode' : ':faill:'
 },
 {
 'name': 'gangs',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/gangs.png",
 'shortcode' : ':gangs:'
 },
 {
 'name': 'hidden',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/hidden.png",
 'shortcode' : ':hidden:'
 },
 {
 'name': 'high',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/high.png",
 'shortcode' : ':high:'
 },
 {
 'name': 'ignoring',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/ignoring.png",
 'shortcode' : ':ignoring:'
 },
 {
 'name': 'indifferent',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/indifferent.png",
 'shortcode' : ':indifferent:'
 },
 {
 'name': 'innocent',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/innocent.png",
 'shortcode' : ':innocent:'
 },
 {
 'name': 'mah_chilling',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/mah_chilling.png",
 'shortcode' : ':mah_chilling:'
 },
 {
 'name': 'nom_nom',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/nom_nom.png",
 'shortcode' : ':nom_nom:'
 },
 {
 'name': 'nose_bleed',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/nose_bleed.png",
 'shortcode' : ':nose_bleed:'
 },
 {
 'name': 'nose_pick',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/nose_pick.png",
 'shortcode' : ':nose_pick:'
 },
 {
 'name': 'oh_noes',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/oh_noes.png",
 'shortcode' : ':oh_noes:'
 },
 {
 'name': 'oh_u',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/oh_u.png",
 'shortcode' : ':oh_u:'
 },
 {
 'name': 'on_fire',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/on_fire.png",
 'shortcode' : ':on_fire:'
 },
 {
 'name': 'psychotic',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/psychotic.png",
 'shortcode' : ':psychotic:'
 },
 {
 'name': 'relief',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/relief.png",
 'shortcode' : ':relief:'
 },
 {
 'name': 'scared',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/scared.png",
 'shortcode' : ':scared:'
 },
 {
 'name': 'secret_laugh',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/secret_laugh.png",
 'shortcode' : ':secret_laugh:'
 },
 {
 'name': 'shocked',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/shocked.png",
 'shortcode' : ':shocked:'
 },
 {
 'name': 'shockedagain',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/shockedagain.png",
 'shortcode' : ':shockedagain:'
 },
 {
 'name': 'shout',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/shout.png",
 'shortcode' : ':shout:'
 },
 {
 'name': 'shy',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/shy.png",
 'shortcode' : ':shy:'
 },
 {
 'name': 'tastey',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/tastey.png",
 'shortcode' : ':tastey:'
 },
 {
 'name': 'teeth_brushing',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/teeth_brushing.png",
 'shortcode' : ':teeth_brushing:'
 },
 {
 'name': 'want',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/want.png",
 'shortcode' : ':want:'
 },
 {
 'name': 'whisper',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/whisper.png",
 'shortcode' : ':whisper:'
 },
 {
 'name': 'whistle',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/whistle.png",
 'shortcode' : ':whistle:'
 },
 {
 'name': 'x3',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/x3.png",
 'shortcode' : 'x3'
 },
 {
 'name': 'yo',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/yo.png",
 'shortcode' : ':yo:'
 },
 {
 'name': 'you_seem_to_be_serious',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/you_seem_to_be_serious.png",
 'shortcode' : ':you_seem_to_be_serious:'
 },
 {
 'name': 'yum',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/yum.png",
 'shortcode' : 'yum:'
 },
 {
 'name': 'zzz',
 'src' : "/bundles/maximcms/images/icons/emoticons/Yolks2/zzz.png",
 'shortcode' : ':zzz:'
 }
 ]
 },
 {
 name: "regular",
 items: []
 }
 ]
 }
 */