function up(input0) {

      'use strict';

      if (typeof input0.stepUp === 'function') {
          try{
         input0.stepUp();
          }catch(ex){
          var step=Number(input0.step);
              input0.value = Number(input0.value) + step;
          }
      }

   }

   function down(input0) {

      'use strict';

      if (typeof input0.stepDown === 'function') {
          try{
         input0.stepDown();
          }catch(ex){
          var step=Number(input0.step);
              input0.value = Number(input0.value) - step;
          }
      }

   }