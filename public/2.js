(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{54:function(t,a,s){"use strict";s.r(a);var n={components:{CoordsInput:s(21).a},data:function(){return{paradas:[]}},computed:{rutas:function(){var t=[];this.paradas.map((function(a){if(-1==t.indexOf(a.ruta.id))return t.push(a.ruta.id),a.ruta}))}}},i=s(3),r=Object(i.a)(n,(function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",{staticClass:"container",attrs:{id:"lookup"}},[s("h4",[t._v("¡Las siguientes rutas te llevan!")]),t._v(" "),t._l(t.rutas,(function(a){return s("div",{key:a.id,staticClass:"row"},[s("div",{staticClass:"col"},[t._v(t._s(a.nombre))]),t._v(" "),a.alias?s("div",{staticClass:"col"},[t._v(t._s(a.alias))]):t._e()])})),t._v(" "),s("coords-input")],2)}),[],!1,null,null,null);a.default=r.exports}}]);