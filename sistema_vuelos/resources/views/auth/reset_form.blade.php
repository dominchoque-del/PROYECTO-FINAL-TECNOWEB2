<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Nueva Contraseña — Sincronía Aérea</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:#0b1120;color:#f1f5f9;min-height:100vh;display:flex;align-items:center;justify-content:center}
.card{background:#111827;border:1px solid rgba(255,255,255,0.07);border-radius:16px;padding:48px;max-width:420px;width:90%}
h1{font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:#f59e0b;margin-bottom:8px}
p{color:#94a3b8;font-size:.85rem;margin-bottom:24px}
label{display:block;font-size:.75rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
input{width:100%;padding:10px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:8px;color:#f1f5f9;font-size:.9rem;margin-bottom:16px}
input:focus{outline:none;border-color:rgba(245,158,11,.5)}
button{width:100%;padding:12px;background:#f59e0b;color:#000;border:none;border-radius:8px;font-weight:700;font-size:.95rem;cursor:pointer}
.msg{margin-top:14px;padding:10px;border-radius:8px;font-size:.85rem;text-align:center;display:none}
.ok{background:rgba(34,197,94,.1);color:#86efac;border:1px solid rgba(34,197,94,.2)}
.err{background:rgba(239,68,68,.1);color:#fca5a5;border:1px solid rgba(239,68,68,.2)}
</style></head>
<body>
<div class="card">
    <h1>🔐 Nueva Contraseña</h1>
    <p>Ingresa y confirma tu nueva contraseña.</p>
    <label>Nueva contraseña</label>
    <input type="password" id="p1" placeholder="Mínimo 6 caracteres">
    <label>Confirmar contraseña</label>
    <input type="password" id="p2" placeholder="Repite la contraseña">
    <button onclick="enviar()">Actualizar Contraseña</button>
    <div id="msg" class="msg"></div>
</div>
<script>
const TOKEN = "{{ $token }}";
const BASE  = "{{ url('/') }}/api";
function enviar(){
    const p1=document.getElementById('p1').value;
    const p2=document.getElementById('p2').value;
    const msg=document.getElementById('msg');
    if(p1!==p2){showMsg('Las contraseñas no coinciden','err');return;}
    if(p1.length<6){showMsg('Mínimo 6 caracteres','err');return;}
    axios.post(BASE+'/auth/reset-password',{token:TOKEN,password:p1})
        .then(r=>{showMsg('✅ '+r.data.mensaje,'ok');setTimeout(()=>location.href='/',2000);})
        .catch(e=>showMsg('❌ '+(e.response?.data?.mensaje||'Error'),'err'));
}
function showMsg(t,c){const m=document.getElementById('msg');m.innerText=t;m.className='msg '+c;m.style.display='block';}
</script>
</body></html>
