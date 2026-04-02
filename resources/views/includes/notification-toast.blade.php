@auth
<div class="toast-container position-fixed top-0 end-0 p-3 mt-5 mt-md-0" style="z-index: 10700;"></div>
<script>
document.addEventListener('DOMContentLoaded',function(){
    window.showToast=function(title,message,type='info',sticky=false){
        const container=document.querySelector('.toast-container');
        if(!container)return;
        const alertAudio=new Audio('{{ asset('dist/sound/alerta-toast.mp3') }}');
        alertAudio.volume=0.8;
        alertAudio.play().catch(e=>console.log('Audio error',e));
        const toastId='toast-'+Math.random().toString(36).substr(2, 9);
        const systemLogo='{{ asset('dist/img/logo.png') }}';
        const finalMessage=title&&!['Sistema','Atención','Error','Éxito','Información','HOSPROGRESO'].includes(title)?`<strong>${title}</strong><br>${message}`:message;
        const toastHTML=`<div id="${toastId}" class="toast toast-show fade show border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img class="avatar avatar-sm avatar-circle me-3" src="${systemLogo}" alt="Logo" width="24" height="24">
                <strong class="me-auto text-dark">HOSPROGRESO</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body text-dark">${finalMessage}</div>
        </div>`;
        container.insertAdjacentHTML('beforeend',toastHTML);
        const toastEl=document.getElementById(toastId);
        const bsToast=new bootstrap.Toast(toastEl,{delay:8000,autohide:!sticky});
        bsToast.show();
        toastEl.addEventListener('hidden.bs.toast',()=>toastEl.remove());
    };
    @if(session('notification')) window.showToast('Información','{{ session('notification')['message'] }}','info',false); @endif
    @if($errors->any()) @foreach($errors->all() as $error) window.showToast('Atención','{{ $error }}','warning',false); @endforeach @endif
    function iniciarWebSockets(){
        if(typeof window.Echo!=='undefined'){
            window.Echo.channel('anuncios').listen('.ReleaseCreated',(e)=>{
                window.showToast(e.title,e.message,e.type,true);
                if(typeof window.refreshNotificationDropdown==='function') window.refreshNotificationDropdown();
            });
        }else{setTimeout(iniciarWebSockets,500);}
    }
    iniciarWebSockets();
});
</script>
@endauth