<!--Шаблон упражнения в дне тренировки-->
<script id="excersicetmp" type="text/x-jsrender">
  <div class="row excersice" data="{{:exid}}">
              <div class="header">
              <h4>{{:name}}</h4>
              <a href="#" class="exclose" onclick="return removeex({{:daynum}}, {{:exnum}})"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
              
              <input type="hidden" name="exercise[{{:daynum}}][{{:exnum}}][excersiceid]" value="{{:exid}}"/> 
              <div class="col-lg-6" style="padding-left: 0">
                <div class="formelement">
                  <label for="cnt">Количество подходов</label>
                    <div class="input-group">
                      <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="this.parentNode.querySelector('input[type=number]').stepDown();"></span>
                      <input type="number" min="1" max="10" id="cnt" value="1" class="form-control" name="exercise[{{:daynum}}][{{:exnum}}][podhod]">
                      <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="this.parentNode.querySelector('input[type=number]').stepUp();"></span>
                    </div>
                  </div>

                <div class="formelement">
                  <label for="cnt">Вес</label>
                    <div class="input-group">
                      <input type="number" min="1" max="100" id="cnt" value="1" class="form-control" name="exercise[{{:daynum}}][{{:exnum}}][minweight]">
                      <span class="input-group-addon numbutton">%</span>
                    </div>
                  </div>
              </div>
              <div class="col-lg-6"  style="padding-right: 0">
              <div class="formelement">
                  <label for="cnt">Количество повторений</label>
                    <div class="input-group">
                      <span class="input-group-addon glyphicon glyphicon-minus numbutton" onclick="this.parentNode.querySelector('input[type=number]').stepDown();"></span>
                      <input type="number" min="1" max="10" id="cnt" value="1" class="form-control" name="exercise[{{:daynum}}][{{:exnum}}][repeat]">
                      <span class="input-group-addon glyphicon glyphicon-plus numbutton" onclick="this.parentNode.querySelector('input[type=number]').stepUp();"></span>
                    </div>
                  </div>
                <div class="formelement">
                  <label for="cnt">Вес</label>
                    <div class="input-group">
                      <input type="number" min="1" max="100" id="cnt" value="1" class="form-control" name="exercise[{{:daynum}}][{{:exnum}}][maxweight]">
                      <span class="input-group-addon numbutton">%</span>
                    </div>
                  </div>
              </div>
            </div>
</script>