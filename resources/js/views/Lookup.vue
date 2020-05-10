<template>
  <div id="lookup" class="container">
      <h4>¡Las siguientes rutas te llevan!</h4>
      <div class="row" v-for="ruta in rutas" :key="ruta.id">
          <div class="col">{{ ruta.nombre }}</div>
          <div class="col" v-if="ruta.alias">{{ ruta.alias }}</div>
      </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            paradas: []
        }
    },
    computed: {
        rutas() {
            let rutasExistentes = [];

            this.paradas.map((p) => {
                if (rutasExistentes.indexOf(p.ruta.id) == -1) {
                    rutasExistentes.push(p.ruta.id);
                    return p.ruta;
                }
            })
        }
    }
}
</script>

<style lang="css" scoped>
</style>
