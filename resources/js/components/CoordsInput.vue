 <template>
   <form id="coordsInput">
       <div class="input-group mb-3">
           <input type="text" class="shadow-lg form-control" v-model="origen" placeholder="Ingresa tu origen">
           <div class="input-group-append">
               <button class="btn btn-primary" type="button" @click="usarUbicacion" title="Usa tu ubicación actual">
                   <target-icon size="1x"></target-icon>
               </button>
           </div>
       </div>
       <div class="lookup-dotted-line"></div>
       <div class="input-group">
           <input type="text" class="shadow-lg form-control" v-model="destino" placeholder="Busca tu destino">
           <div class="input-group-append">
               <button class="btn btn-primary">
                   <search-icon size="1x"></search-icon>
               </button>
           </div>
       </div>
   </form>
 </template>

 <script>
 import { TargetIcon, SearchIcon } from 'vue-feather-icons'

 export default {
     data() {
         return {
             origen: '',
             destino: ''
         }
     },
     components: { TargetIcon, SearchIcon },
     methods: {
         usarUbicacion() {
             let instance = this
             navigator.geolocation.getCurrentPosition((pos) => {
                 instance.origen = pos.coords.latitude + " " + pos.coords.longitude
             })
         },
         buscarParadas() {
             axios.get('paradas-cercanas', {
                 params: {
                     origen: instance.origen,
                     destino: instance.destino
                 }
             }).then((r) => {
                 intance.$emit('paradasActualizadas', r.data)
             })
         }
     },
 }
 </script>
