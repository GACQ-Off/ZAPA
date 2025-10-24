<?php
// Simulación de datos (sin BD)
$productos = [
  ["id_pro" => 1, "nombre_producto" => "Manzana", "precio" => 1.20, "codigo" => "MAN01", "cantidad" => 100, "id_tipo_cuenta" => 2, "valor_iva" => 16],
  ["id_pro" => 2, "nombre_producto" => "Banano", "precio" => 0.80, "codigo" => "BAN02", "cantidad" => 150, "id_tipo_cuenta" => 2, "valor_iva" => 16],
  ["id_pro" => 3, "nombre_producto" => "Servicio Express", "precio" => 5.00, "codigo" => "EXP03", "cantidad" => 999, "id_tipo_cuenta" => 1, "valor_iva" => 16],
];
$tasa = 36.50;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Venta Rápida - Sistema Yu</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
:root{--accent:#0077ff;--danger:#e60000;--bg:#f7f9fc;--card:#ffffff;--text:#222}
*{box-sizing:border-box;font-family:system-ui, sans-serif}
body{margin:0;background:var(--bg);color:var(--text)}
header{background:var(--accent);color:#fff;padding:.75rem 1rem;display:flex;align-items:center;justify-content:space-between}
#buscador_rapido{position:sticky;top:0;z-index:10;width:100%;padding:.75rem 1rem;font-size:1.1rem;border:none;border-bottom:1px solid #ccc}
#resultados_busqueda{position:absolute;z-index:20;background:var(--card);width:100%;max-height:220px;overflow-y:auto;border:1px solid #ccc;border-top:none}
.item{padding:.5rem .75rem;cursor:pointer;display:flex;justify-content:space-between}
.item:hover,.item.selected{background:var(--accent);color:#fff}
main{display:flex;flex-wrap:wrap;gap:1rem;padding:1rem}
#carrito{flex:2;min-width:300px;background:var(--card);border-radius:.5rem;padding:1rem}
#resumen{flex:1;min-width:260px;background:var(--card);border-radius:.5rem;padding:1rem;position:sticky;top:4rem;height:fit-content}
.linea{display:flex;justify-content:space-between;align-items:center;padding:.35rem 0}
.linea button{background:var(--danger);color:#fff;border:none;border-radius:3px;padding:.25rem .5rem;cursor:pointer}
#btn_pago{width:100%;background:var(--accent);color:#fff;border:none;padding:.75rem;border-radius:.3rem;font-size:1.1rem;cursor:pointer}
.modal{position:fixed;inset:0;background:rgba(0,0,0,.45);display:none;align-items:center;justify-content:center}
.modal-content{background:var(--card);width:90%;max-width:420px;border-radius:.5rem;padding:1rem}
.modal h3{margin-top:0}
.modal-row{display:flex;justify-content:space-between;margin:.5rem 0}
.modal-row input{width:45%;padding:.5rem}
.toast{position:fixed;bottom:1rem;right:1rem;background:#222;color:#fff;padding:.75rem 1.2rem;border-radius:.3rem;animation:fade .3s}
@keyframes fade{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
kbd{background:#eee;border:1px solid #ccc;border-radius:3px;padding:2px 4px;font-size:.85em}
</style>
</head>
<body>
<header>
  <span><strong>Venta Rápida</strong> - Sistema Yu</span>
  <span><kbd>Ctrl+B</kbd> buscar <kbd>Ctrl+P</kbd> pagar</span>
</header>

<input id="buscador_rapido" type="text" placeholder="Buscar producto por nombre o código...">
<div id="resultados_busqueda"></div>

<main>
  <section id="carrito">
    <h3>Carrito</h3>
    <div id="items"></div>
    <button id="agregar_linea" style="margin-top:.5rem">+ Agregar línea manual</button>
  </section>

  <aside id="resumen">
    <h3>Resumen</h3>
    <div class="linea"><span>Subtotal USD:</span><span id="sub_usd">0.00</span></div>
    <div class="linea"><span>IVA USD:</span><span id="iva_usd">0.00</span></div>
    <div class="linea"><strong>Total USD:</strong><strong id="total_usd">0.00</strong></div>
    <div class="linea"><span>Total BS:</span><span id="total_bs">0.00</span></div>
    <hr>
    <div class="linea">Cliente: <span id="cliente_txt">Público</span> <a href="#" id="cambiar_cliente">Cambiar</a></div>
    <button id="btn_pago">💳 PAGAR</button>
  </aside>
</main>

<!-- Modal de pago -->
<div id="modal_pago" class="modal">
  <div class="modal-content">
    <h3>Pago</h3>
    <div class="modal-row">
      <label>Monto recibido USD:</label>
      <input id="monto_usd" type="number" step="0.01" min="0" value="0">
    </div>
    <div class="modal-row">
      <label>Monto recibido BS:</label>
      <input id="monto_bs" type="number" step="0.01" min="0" value="0">
    </div>
    <div class="modal-row">
      <button id="btn_exacto">Pago exacto</button>
      <button id="btn_bsal dia">BS al día</button>
    </div>
    <hr>
    <div class="modal-row"><span>Cambio USD:</span><span id="cambio_usd">0.00</span></div>
    <div class="modal-row"><span>Cambio BS:</span><span id="cambio_bs">0.00</span></div>
    <div style="display:flex;gap:.5rem;margin-top:1rem">
      <button id="confirmar_pago" style="flex:1">Confirmar</button>
      <button id="cancelar_pago" style="flex:1;background:#ccc">Cancelar</button>
    </div>
  </div>
</div>

<script>
const productos = <?= json_encode($productos) ?>;
const tasa = <?= $tasa ?>;
let carrito = [];
const d = document;
const $ = id => d.getElementById(id);

/* ---- Toast ---- */
function toast(msg, tipo='success'){
  const t = d.createElement('div'); t.className='toast'; t.textContent=msg;
  d.body.appendChild(t); setTimeout(()=>t.remove(),3000);
}
/* ---- Busqueda ---- */
let idx = -1;
$('buscador_rapido').addEventListener('input', e=>{
  const q = e.target.value.toLowerCase();
  const res = $('resultados_busqueda'); res.innerHTML=''; idx=-1;
  if(!q){res.style.display='none';return}
  const filtro = productos.filter(p=>p.nombre_producto.toLowerCase().includes(q)||p.codigo.toLowerCase().includes(q));
  res.style.display='block';
  filtro.forEach((p,i)=>{
    const div = d.createElement('div'); div.className='item'; div.dataset.id=p.id_pro;
    div.innerHTML=`<span>${p.nombre_producto}</span><span>$${p.precio}</span>`;
    div.onclick=()=>agregar(p);
    res.appendChild(div);
  });
});
d.addEventListener('keydown',e=>{
  const items=[...$('resultados_busqueda').querySelectorAll('.item')];
  if(e.key==='ArrowDown'){idx=(idx+1)%items.length;actualizarSel(items);}
  if(e.key==='ArrowUp'){idx=(idx-1+items.length)%items.length;actualizarSel(items);}
  if(e.key==='Enter'&&idx>=0){agregar(productos.find(p=>p.id_pro==items[idx].dataset.id)); $('buscador_rapido').value=''; $('resultados_busqueda').innerHTML=''; idx=-1;}
  if(e.key==='Escape'){$('resultados_busqueda').innerHTML=''; idx=-1;}
  if(e.ctrlKey&&e.key==='b'){e.preventDefault(); $('buscador_rapido').focus()}
  if(e.ctrlKey&&e.key==='p'){e.preventDefault(); abrirPago()}
});
function actualizarSel(items){items.forEach((it,i)=>it.classList.toggle('selected',i===idx))}
/* ---- Carrito ---- */
function agregar(p){
  const linea = carrito.find(l=>l.id_pro===p.id_pro);
  if(linea){linea.cantidad++}else{carrito.push({...p,cantidad:1})}
  render(); toast('Agregado: '+p.nombre_producto)
}
function render(){
  const cont=$('items'); cont.innerHTML='';
  carrito.forEach((l,i)=>{
    const div=d.createElement('div'); div.className='linea';
    div.innerHTML=`
      <span>${l.nombre_producto} (${l.cantidad})</span>
      <span>$${(l.cantidad*l.precio).toFixed(2)}
      <button onclick="quitar(${i})">✖</button></span>`;
    cont.appendChild(div);
  })
  calcular()
}
function quitar(i){carrito.splice(i,1);render();toast('Producto eliminado','error')}
function calcular(){
  let sub=0, iva=0;
  carrito.forEach(l=>{sub+=l.cantidad*l.precio; iva+=l.cantidad*l.precio*l.valor_iva/100});
  const total=sub+iva;
  $('sub_usd').textContent=sub.toFixed(2);
  $('iva_usd').textContent=iva.toFixed(2);
  $('total_usd').textContent=total.toFixed(2);
  $('total_bs').textContent=(total*tasa).toFixed(2);
}
/* ---- Pago ---- */
$('btn_pago').onclick=abrirPago;
function abrirPago(){
  if(!carrito.length){toast('Carrito vacío','error');return}
  $('modal_pago').style.display='flex';
  const total=parseFloat($('total_usd').textContent);
  $('monto_usd').value=total; $('monto_bs').value='';
  calcularCambio()
}
['monto_usd','monto_bs'].forEach(id=>$(id).oninput=calcularCambio);
function calcularCambio(){
  const total=parseFloat($('total_usd').textContent);
  const usd=parseFloat($('monto_usd').value)||0;
  const bs=parseFloat($('monto_bs').value)||0;
  const usdEquiv=bs/tasa;
  const recibido=usd+usdEquiv;
  const cambio=recibido-total;
  $('cambio_usd').textContent=Math.max(0,cambio).toFixed(2);
  $('cambio_bs').textContent=(Math.max(0,cambio)*tasa).toFixed(2);
}
$('btn_exacto').onclick=()=>{const t=parseFloat($('total_usd').textContent); $('monto_usd').value=t; $('monto_bs').value=''; calcularCambio()}
$('btn_bsal dia').onclick=()=>{const t=parseFloat($('total_usd').textContent); $('monto_bs').value=(t*tasa).toFixed(2); $('monto_usd').value=''; calcularCambio()}
$('cancelar_pago').onclick=()=>$('modal_pago').style.display='none';
$('confirmar_pago').onclick=()=>{
  toast('Venta completada ✅');
  carrito=[]; render(); $('modal_pago').style.display='none';
}
/* ---- Cliente ---- */
$('cambiar_cliente').onclick=e=>{
  e.preventDefault();
  const nom=prompt('Nombre del cliente (dejar vacío para Público):');
  $('cliente_txt').textContent=nom||'Público';
}
</script>
</body>
</html>







