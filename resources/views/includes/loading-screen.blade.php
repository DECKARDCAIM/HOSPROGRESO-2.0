<style>
    #gpl{position:fixed;inset:0;z-index:99999;background:#0B1B3D;display:flex;align-items:center;justify-content:center;pointer-events:auto;}
    #gpl.gpl-out{transition:opacity .55s cubic-bezier(.4,0,.2,1);opacity:0;pointer-events:none;}
</style>
<div id="gpl">
    <div class="text-center">
        <img src="{{ asset('dist/img/Logotipo-white.svg') }}" alt="Logo" class="img-fluid mb-4" style="max-width: 300px;">
        <div class="mt-4">
            <div class="spinner-border text-white" role="status" style="width: 2.5rem; height: 2.5rem; border-width: 0.2rem;">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p id="gpl-txt" class="text-white mt-3 fs-4 fw-light tracking-wide">Iniciando...</p>
        </div>
        <div class="progress mt-4 mx-auto overflow-hidden" style="height: 4px; width: 250px; background-color: rgba(255,255,255,0.1); border-radius: 10px;">
            <div class="progress-bar bg-white" id="gpl-bar" role="progressbar" style="width: 0%;"></div>
        </div>
    </div>
</div>
<script type="module">
(function(){
    'use strict';
    var overlay=document.getElementById('gpl'),bar=document.getElementById('gpl-bar'),txt=document.getElementById('gpl-txt'),pct=0,loaded=0,total=0,syncDone=false,hiding=false;
    var MSGS=[{at:0,text:'Iniciando...'},{at:15,text:'Sincronizando archivos del sistema...'},{at:50,text:'Optimizando base de datos y caché...'},{at:82,text:'Cargando interfaz...'},{at:100,text:'\u00a1Sincronizado!'}];
    function msgFor(p){var out=MSGS[0].text;for(var i=0;i<MSGS.length;i++){if(p>=MSGS[i].at)out=MSGS[i].text;}return out;}
    function setBar(p,ms){p=Math.min(100,Math.max(0,Math.round(p)));pct=p;if(bar){bar.style.transition=ms>0?'width '+ms+'ms cubic-bezier(.4,0,.2,1)':'none';bar.style.width=p+'%';}if(txt)txt.textContent=msgFor(p);}
    function hide(){if(!overlay||hiding)return;hiding=true;setBar(100,380);setTimeout(function(){overlay.classList.add('gpl-out');document.body.style.overflow='';overlay.addEventListener('transitionend',function onEnd(){overlay.removeEventListener('transitionend',onEnd);overlay.style.display='none';});},1000);}
    function watchAssets(){document.body.style.overflow='hidden';var nodes=document.querySelectorAll('img, script[src], link[rel="stylesheet"], link[rel="stylesheet"], link[rel="preload"]');total=nodes.length;loaded=0;if(total===0){syncDone=true;hide();return;}var startPct=pct;function onAsset(){loaded++;var next=startPct+((loaded/total)*(98-startPct));setBar(next,260);if(loaded>=total){syncDone=true;hide();}}nodes.forEach(function(el){if(el.tagName==='IMG'&&el.complete){onAsset();}else{el.addEventListener('load',onAsset,{once:true});el.addEventListener('error',onAsset,{once:true});}});}
    window.showPageLoader=function(label){if(!overlay)return;hiding=false;overlay.style.display='flex';overlay.classList.remove('gpl-out');overlay.style.opacity='1';overlay.style.pointerEvents='auto';document.body.style.overflow='hidden';setBar(0,0);if(txt)txt.textContent=label||'Cargando...';setTimeout(function(){setBar(45,550);},60);setTimeout(function(){setBar(68,900);},650);};
    window.hidePageLoader=hide;
    document.addEventListener('click',function(e){var link=e.target.closest('a[href]');if(!link)return;var href=link.getAttribute('href')||'',target=link.getAttribute('target')||'';if(!href||href.startsWith('#')||href.startsWith('javascript:')||href.startsWith('mailto:')||href.startsWith('tel:')||target==='_blank'||e.ctrlKey||e.metaKey||e.shiftKey||link.closest('form'))return;window.showPageLoader('Cargando...');});
    document.addEventListener('submit',function(e){if(e.target.getAttribute('target')==='_blank'||!e.target.checkValidity())return;window.showPageLoader('Procesando...');});
    window.addEventListener('pageshow',function(e){if(e.persisted)hide();});
    if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',watchAssets);}else{watchAssets();}
    window.addEventListener('load',function(){if(!syncDone){syncDone=true;hide();}});
}());
</script>
