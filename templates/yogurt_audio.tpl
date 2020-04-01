<{include file="db:yogurt_navbar.tpl"}>
<{ if $isOwner }>
    <div id="yogurt-audio-form" class="outer">
        <h2 id="yogurt-audio-form-title" class="head">
            <{$lang_addaudios}>
        </h2>
        <form name="form_audios" id="form_audios" action="submitaudio.php" method="post" onsubmit="return xoopsFormValidate_form_audios();" enctype="multipart/form-data">
            <{$token}>
            <p class="even">
            <{$lang_audiohelp}></p>
            <p class="odd">
                <label for="title">
                    <{$lang_titleLabel}>
                    :
                </label>
                <input type='text' name='title' id='title' size='50' maxlength='250' value=''>
            </p>
            <p class="even">
                <label for="author">
                    <{$lang_authorLabel}>
                    :
                </label>
<input type='text' name='author' id='author' size='50' maxlength='250' value=''>
            </p>
                         <p class="odd"><label class="yogurt-audio-label" for="sel_file"><{$lang_selectaudio}></label><input type='hidden' name='MAX_FILE_SIZE' value='<{$max_youcanupload}>'><input type='file' name='sel_audio' id='sel_audio'><input type='hidden' name='xoops_upload_file[]' id='xoops_upload_file[]' value='sel_audio'> </p>
            <p class="foot">
                <input type='submit' class='formButton' name='submit_button'  id='submit_button' value='<{$lang_submitValue}>'>
            </p>
        </form>

    </div>
<{ /if}>


<div id="yogurt-audio-allaudiocontainer" class="outer">
    <h2 id="yogurt-audio-allaudiotitle" class="head">
    <{$player_from_list}>
    </h2>

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="240" height="20" id="dewplayer" align="middle"><param name="wmode" value="transparent"><param name="allowScriptAccess" value="sameDomain"><param name="movie" value="audioplayers/dewplayer-multi.swf?mp3=<{$audio_list}>"><param name="quality" value="high"><param name="bgcolor" value="FFFFFF"><embed src="audioplayers/dewplayer-multi.swf?mp3=<{$audio_list}>" quality="high" bgcolor="FFFFFF" width="240" height="20" name="dewplayer" wmode="transparent" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object>

</div>

<div id="yogurt-audio-container" class="outer">
    <h2 id="yogurt-audio-title" class="head">
        <a href="<{$xoops_url}>/userinfo.php?uid=<{$owner_uid}>">
            <{$lang_audios}>
        </a>
    </h2>
    <{if $nb_audio<=0}>
        <h2>
            <{$lang_noaudioyet}>
        </h2>
    <{/if}>



    <{section name=i loop=$audios}>
        <div class="yogurt-audio-container <{cycle values="odd,even"}>">
            <div class="yogurt-audio">
                <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="200" height="20" id="dewplayer" align="middle">
<param name="wmode" value="transparent"><param name="allowScriptAccess" value="sameDomain"><param name="movie" value="audioplayers/dewplayer.swf?mp3=<{$xoops_url}>/uploads/yogurt/mp3/<{$audios[i].url}>&amp;showtime=1"><param name="quality" value="high"><param name="bgcolor" value="FFFFFF"><embed src="audioplayers/dewplayer.swf?mp3=<{$xoops_url}>/uploads/yogurt/mp3/<{$audios[i].url}>&amp;showtime=1" width="200" height="20" align="middle" quality="high" bgcolor="FFFFFF" name="dewplayer" wmode="transparent" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object>
            </div>
            <div class="yogurt-audio-details">
                <p class="yogurt-audio-desc">
                    <{$audios[i].title}> <{$audios[i].author}>
                    <div class="yogurt-audio-metainfocontainer">
                    <h4 class="yogurt-audio-metainfo"> <{$lang_meta}></h4>
                    <p class="yogurt-audio-meta-title"><span class="yogurt-audio-meta-label"> <{$lang_title}>:</span> <{$audios[i].meta.Title}></p>
                    <p class="yogurt-audio-meta-title"><span class="yogurt-audio-meta-label"> <{$lang_album}>:</span> <{$audios[i].meta.Album}></p>
                    <p class="yogurt-audio-meta-title"><span class="yogurt-audio-meta-label"> <{$lang_artist}>:</span> <{$audios[i].meta.Artist}></p>
                    <p class="yogurt-audio-meta-title"><span class="yogurt-audio-meta-label"> <{$lang_year}>:</span> <{$audios[i].meta.Year}></p>
                    </div>

                </p> <{ if $isOwner==1 }>
                <form action="delaudio.php" method="post" id="deleteform" class="yogurt-audio-forms">
                    <input type="hidden" value="<{$audios[i].id}>" name="cod_audio">
                    <{$token}>
                    <input name="submit" type="image" alt="<{$lang_delete}>" title="<{$lang_delete}>" src="assets/images/dele.gif"/>
                </form>


                <{ /if}>
            </div>
        </div>
    <{/section}>
</div>
<div style="clear:both;width:100%"></div>
<div id="yogurt-navegacao">
    <{$pageNav}>
</div>
<div style="clear:both;width:100%"></div>
<{include file="db:yogurt_footer.tpl"}>
