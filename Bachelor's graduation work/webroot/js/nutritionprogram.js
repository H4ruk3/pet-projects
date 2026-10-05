

var numbers = ['Первый', 'Второй', 'Третий', 'Четвёртый', 'Пятый', 'Шестой', 'Седьмой', 'Восьмой', 'Девятый', 'Десятый'];

class NutritiondaysList extends React.Component {
	constructor(props) {
    	super(props);
    	this.state = {daycnt: (typeof eatingprograminfo != 'undefined')?eatingprograminfo.daycnt:1, eatingcnt: (typeof eatingprograminfo != 'undefined')?eatingprograminfo.eatingcnt:routines[0].eatCount};
    	this.addDay = this.addDay.bind(this);
    	this.onChange = this.onChange.bind(this);
    	this.onDelete = this.onDelete.bind(this);
      this.onSubmit = this.onSubmit.bind(this);
      this.validday = this.validday.bind(this);
    	/*this.source = 
    [
        {routinename: "Распорядок 1", eatingcount: 4},
        {routinename: "Распорядок 2", eatingcount: 3},
        {routinename: "Распорядок 3", eatingcount: 5},
        {routinename: "Распорядок 4", eatingcount: 2},
    ];*/
    this.days = [1];
    this.validdaystatus = []
    this.validdaystatus[0] = false;
    this.daykey = 2;
    this.state.selected = (typeof eatingprograminfo != 'undefined')?eatingprograminfo.routine_id:0;
    this.selectedIndex = (typeof eatingprograminfo != 'undefined')?eatingprograminfo.routine_number:0;
	}

  validday(id, st) {
    console.log(st);
    this.validdaystatus[id] = st;
  }
  removeday(id) {
    this.validdaystatus.splice(id, 1);
  }

  checkdays() {
    this.valid = true;
    this.validdaystatus.forEach(function(item){
      if (item == false)
        this.valid = false;
    }, this)
    return this.valid;
  }

  onSubmit(e) {
    //e.preventDefault();
    if (this.refs.name.checkValidity()) {
      if (this.checkdays())
        return true;
      else
      {
        $("#alertplace")[0].innerHTML = '<div class="alert alert-danger" role="alert"> \
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
  <strong>Ошибка!</strong> В дне тренировки нет ни одного упражнения. \
</div>';
   var alignWithTop = true;
 $("#alertplace")[0].scrollIntoView(alignWithTop);
      e.preventDefault();
      return false;
      }
    } else {
    this.refs.formgroup.classList.add('has-error');
    this.refs.formgroup.classList.remove('has-success');
        //добавить к glyphicon класс glyphicon-ok, удалить glyphicon-remove
    this.refs.glyphicon.classList.add('glyphicon-remove');
    this.refs.glyphicon.classList.remove('glyphicon-ok'); }
    return false;
    //console.log("submit");
    //alert("submit");
    
  }
	onDelete(id){
		this.days.splice(id,1);
		var daycnt = this.state.daycnt
		daycnt--;
		this.setState({daycnt: daycnt});
    this.validdaystatus.splice(id, 1)
  	}
	/*onChange() {
		var routine = this.refs.routine;
		//var i = this.refs.routine.index-1;
		var i = routine.selectedIndex;
		console.log(i);
		this.setState({selected: i});
		//console.log(this.source);
		this.setState({eatingcnt: routines[i].eatCount});
	}*/
  onChange(event) {
    this.setState({selected: event.target.value});
    this.selectedIndex = event.target.selectedIndex;
    this.setState({eatingcnt: routines[this.selectedIndex].eatCount});
  }

	render() { 
		var daycnt = this.state.daycnt;
		var days = [];
		for (var i = 0; i<daycnt; i++){
			days[days.length] = <Nutritionday key={this.days[i]} number={i} routine={this.selectedIndex} nutritioncount={this.state.eatingcnt} onDelete={this.onDelete} onDayStatus={this.validday}/>
		}
		
    var options = [];
    for(var i = 0; i < routines.length; i++) {
      options[options.length] = <option key={i} value={routines[i].id}>{routines[i].name}</option>
    }

    /*var options = routines.map(function(item, index) {
  			return (
          <option value={item.id} selected={index==this.selected}>{item.name}</option>
        );
        if (index==this.selected)
        return (
    			<option value={item.id} selected=true>{item.name}</option>
  			); else return (
          <option value={item.id}>{item.name}</option>
        );

		});*/

		return  <div className = "col-lg-6" style={{'marginTop': '-20px'}}>
        <form method="POST" action={(mode=='CREATE')?"/nutritionprogram/create":backurl} className="form-horizontal" onSubmit={this.onSubmit}>
     		<div ref="leftblock" className="fixblock left" style={{height: '65vh'}}>
      			<h4>{(mode=='CREATE')?"Создание программы питания":"Редактирование программы питания"}</h4>
      			<div ref="formgroup" className="form-group has-feedback" style={{margin: '0px'}}>
              <label htmlFor="name">Название</label>
              <div className="col-xs-12"  style={{'padding' : '0px'}}>
                <div className="input-group" style={{'width': '100%'}}>
                  <input ref="name" type="text" name="name" className="form-control" id="name" defaultValue={(typeof eatingprograminfo != 'undefined')?eatingprograminfo.name:""} style={{ 'border-radius': '4px'}} required/>
                </div>
                <span ref="glyphicon" className="glyphicon form-control-feedback"></span>
              </div>
            </div>






            
      			<div className="form-group has-feedback" style={{margin: '0px'}}>
      				<label htmlFor="routine">Распорядок дня</label>
      				<div className="col-xs-12" style={{'padding' : '0px'}}>
                <div className="input-group" style={{'width': '100%'}}>
              <select ref="routine" className="form-control" name="routine" value={this.state.selected} onChange={this.onChange} style={{ 'border-radius': '4px'}}>
     					{options}
   					</select>
            </div>
                
              </div>
      			</div>
            <div id="alertplace"></div>
            <div className="submit">
      			<input type="button" value="Добавить день" className="btn btn-primary" onClick={this.addDay}/>
            </div>
      			<div className="editor">
         			<div >
         			{days}
         			</div>
         		</div>
      		</div>
          <div className="submit"><input type="submit" className="btn btn-primary" id="сохранить" value="Сохранить" /></div>
          </form>
      	</div>;
	}
  componentDidMount () {
    jQuery(this.refs.leftblock).perfectScrollbar();
  }

	addDay() {
		var dayc = this.state.daycnt;
		dayc++;
		this.setState({daycnt: dayc});
		this.days[this.days.length] = this.daykey;
		this.daykey++;
	}
}

class Nutritionday extends React.Component {
	constructor(props) {
    	super(props);
    	console.log("update days");
    	console.log(this.props.nutritioncount);
    	this.state = {isCollapse: false, nutritioncount: props.nutritioncount};
    	this.eatstate = [];
      this.toggle = this.toggle.bind(this);
    	this.removeday = this.removeday.bind(this);
      this.onDayStatus = this.onDayStatus.bind(this);
	}
  onDayStatus(id, st) {
    this.eatstate[id] = st;
    this.valid = true;
    this.eatstate.forEach(function(item){
      if (item == false)
        this.valid = false;
    }, this)
    this.props.onDayStatus(this.props.number, this.valid);
  }
	removeday() {
		//e.preventDefault();
		console.log("removeday");
		this.props.onDelete(this.props.number);
	}
	render() {
		console.log("render day");
		var isCollapse = this.state.isCollapse;
		let button = null;
    	if (isCollapse) {
      		button = <span className = "glyphicon glyphicon-chevron-down"></span>;
    	} else {
      		button = <span className = "glyphicon glyphicon-chevron-up"></span>;
    	}
    	var nutritions = [];
    	for (var i = 0; i < this.props.nutritioncount; i++) {
    		nutritions[nutritions.length] = <Nutrition key={i} number={i} dayid={this.props.number} id={routines[this.props.routine].eating[i].id} onDayStatus={this.onDayStatus}/>;
    	}
		return <div id = {this.props.number} className="c1">
              <div className="dayheader">
      				<h4 >
        				<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" onClick={()=>this.toggle("#collapse1")/*this.toggle*/}>День {this.props.number+1} {button}</a> 
        				<a className="glyphicon glyphicon-remove-circle" style={{float: 'right'}} href="#" onClick={this.removeday}></a>
      				</h4>
              </div>
      				<div id={"collapse"+this.props.number+1} ref="collapse" className = "panel-collapse collapse in" aria-expanded = "true">
      					{nutritions}
    				</div>
  				</div>;
	}



	componentDidMount(){
		var col = this.refs.collapse;
		//col.addEventListener("show.bs.collapse", this.toggle1);
	}

	toggle1(obj) {
		console.log("togle");
	}

	toggle(objName) {
		console.log("togle");
		var isCollapse = this.state.isCollapse;
		if (isCollapse)
			this.setState({isCollapse: false});
		else 
			this.setState({isCollapse: true});
 		//var obj = $(objName),
 		var obj = jQuery(this.refs.collapse);
 		var blocks = $("div[id*='menu-']");
 
 		if (obj.css("display") != "none") {
 			obj.animate({ height: 'hide' }, 500);
 		} else {
 			var visibleBlocks = $("div[id*='menu-']:visible");
 			if (visibleBlocks.length < 1) {
 				obj.animate({ height: 'show' }, 500);
 			} else {
 				$(visibleBlocks).animate({ height: 'hide' }, 500, function() {
 					obj.animate({ height: 'show' }, 500);
 				}); 
 			}
 		}
	}
}

class Nutrition extends React.Component {
	constructor(props) {
    	super(props);
    	this.key = 3;
    	this.products = (typeof existsproducts !== 'undefined' && this.props.dayid in existsproducts && this.props.id in existsproducts[this.props.dayid])?existsproducts[this.props.dayid][this.props.id]:[];
    	var food = { proteins: 0, fats: 0, hidrocarbonats: 0, colories: 0};
    	for (var i = 0; i< this.products.length; i++) {
    		food.proteins = (parseFloat(food.proteins) + parseFloat(this.products[i].proteins)).toFixed(2);
    		food.fats = (parseFloat(food.fats) + parseFloat(this.products[i].fats)).toFixed(2);
    		food.hidrocarbonats = (parseFloat(food.hidrocarbonats) + parseFloat(this.products[i].hidrocarbonats)).toFixed(2);
    		food.colories = (parseFloat(food.colories) + parseFloat(this.products[i].colories)).toFixed(2);
    	}

    	this.state = {value: '100', products: this.products, food: food};
    	//var food = { proteins: '0', fats: "0", hidrocarbonats: '0', colories: '0'};
    	this.handleChange = this.handleChange.bind(this);
    	this.changesumm = this.changesumm.bind(this);
    	this.delete = this.delete.bind(this);
      this.onDayStatus = this.onDayStatus.bind(this);
       this.props.onDayStatus(this.props.number, false);
  	}

    onDayStatus() {
    if (this.state.products.length > 0)
      this.props.onDayStatus(this.props.number, true);
    else
      this.props.onDayStatus(this.props.number, false);
  }

  	delete(id){
		var prod = this.state.products;
		var obj = {}
		obj.proteins = -1 * parseFloat(prod[id].proteins);
		obj.fats = -1 * parseFloat(prod[id].fats);
		obj.hidrocarbonats = -1 * parseFloat(prod[id].hidrocarbonats);
		obj.colories = -1 * parseFloat(prod[id].colories);
		this.changesumm(obj);
		prod.splice(id,1);
		this.setState({products: prod});
  	}
  	changesumm(object) {
		var food = this.state.food;
		food.proteins = (parseFloat(food.proteins) + parseFloat(object.proteins)).toFixed(2);
    	food.fats = (parseFloat(food.fats) + parseFloat(object.fats)).toFixed(2);
    	food.hidrocarbonats = (parseFloat(food.hidrocarbonats) + parseFloat(object.hidrocarbonats)).toFixed(2);
    	food.colories = (parseFloat(food.colories) + parseFloat(object.colories)).toFixed(2);
    	this.setState({food: food});
	}
	render() {
		var products = [];
		for (var i =0; i < this.state.products.length; i++) {
			products[products.length] = <Nutritionproduct id={i} dayid={this.props.dayid} name={this.props.id} productid={this.state.products[i].id} key={this.state.products[i].key} product={this.state.products[i]} onChangeSumm={this.changesumm} onDelete={this.delete}/>
		}

		return <article className="training-day">
               <h3>{numbers[this.props.number]} прием пищи</h3>
               <table className="nutrition-part">
            <thead>
            <tr>
                <th className="table-header nutrition-caption-name">Продукт</th>
                <th className="table-header nutrition-caption-weight">Граммы</th>
                <th className="table-header nutrition-caption-pfc">Б</th>
                <th className="table-header nutrition-caption-pfc">Ж</th>
                <th className="table-header nutrition-caption-pfc">У</th>
                <th className="table-header nutrition-caption-calories">Ккал</th>
                <th className="button-contains nutrition-caption-delete"></th>
            </tr>
            </thead>
            <tbody>
            {products}
            </tbody>
            <tfoot>
            <tr className="total">
                <td colSpan="2" className="table-header">Норма</td>
                <td>{parseFloat(bgunorm.avePrCf).toFixed(2)}</td>
                <td>{parseFloat(bgunorm.aveFtCf).toFixed(2)}</td>
                <td>{parseFloat(bgunorm.aveCaCf).toFixed(2)}</td>
                <td>500</td>
                <td className="button-contains"></td>
            </tr>
            <tr>
                <td colSpan="2" className="table-header">Фактическое значение</td>
                <td>{this.state.food.proteins}</td>
                <td>{this.state.food.fats}</td>
                <td>{this.state.food.hidrocarbonats}</td>
                <td>{this.state.food.colories}</td>
                <td className="button-contains"></td>
            </tr>
            </tfoot>
        </table>

               <div className="droparea" ref="droparea">
                  Перетащите сюда продукты, чтобы добавить их на этот день.
               </div>
            </article>;
	}
	componentDidMount() {
		var main = this;
		var drop = function(event, ui) {
			var products = main.state.products;
      			var f = JSON.parse(JSON.stringify(foods[ui.draggable[0].id]));
      			f.key = main.key;
      			main.key++;
      			products[products.length] = f;

      			var food = main.state.food;
      			food.proteins = (parseFloat(food.proteins) + parseFloat(foods[ui.draggable[0].id].proteins)).toFixed(2);
    			food.fats = (parseFloat(food.fats) + parseFloat(foods[ui.draggable[0].id].fats)).toFixed(2);
    			food.hidrocarbonats = (parseFloat(food.hidrocarbonats) + parseFloat(foods[ui.draggable[0].id].hidrocarbonats)).toFixed(2);
    			food.colories = (parseFloat(food.colories) + parseFloat(foods[ui.draggable[0].id].colories)).toFixed(2);
      			main.setState({products: products, food: food});
            main.onDayStatus();
		}

		jQuery(this.refs.droparea).droppable({
    		drop: drop
  		});
	}
	handleChange(event) {
		console.log("handleChange");
    	this.setState({value: event.target.value});
  	}
}

class Nutritionproduct extends React.Component {
	constructor(props) {
    	super(props);
    	this.state = {value: (typeof(this.props.product.cnt)!='undefined')?this.props.product.cnt:100, product: this.props.product};
      if (typeof(this.props.product.cnt)!='undefined') {
        var val = this.props.product.cnt / 100;
        var oldval = 1;
        var food = this.state.product;
        var prot = food.proteins;
        var fat = food.fats;
        var hid = food.hidrocarbonats;
        var col = food.colories;
        food.proteins = ((food.proteins / oldval) * val).toFixed(2);
        food.fats = ((food.fats / oldval) * val).toFixed(2);
        food.hidrocarbonats = ((food.hidrocarbonats / oldval) * val).toFixed(2);
        food.colories = ((food.colories / oldval) * val).toFixed(2);
        var obj = { proteins: food.proteins-prot, fats: food.fats-fat, hidrocarbonats: food.hidrocarbonats - hid, colories: food.colories - col};
        this.state = {value: this.props.product.cnt, product: food};
        this.props.onChangeSumm(obj);
      }


    	this.handleChange = this.handleChange.bind(this);
    	this.onDelete = this.onDelete.bind(this);
    }
	render() {
		return (
			<tr>
                <td>{this.state.product.name}</td>
                <td className="input-edit-contains">
                  <input type="hidden" name={'foods['+this.props.dayid+']['+this.props.name+']['+this.props.id+'][0]'} value={this.props.productid} />
                  <input type="number" name={'foods['+this.props.dayid+']['+this.props.name+']['+this.props.id+'][1]'} value={this.state.value} className="input-edit" onChange={this.handleChange}/></td>
                <td>{this.state.product.proteins}</td>
                <td>{this.state.product.fats}</td>
                <td>{this.state.product.hidrocarbonats}</td>
                <td>{this.state.product.colories}</td>
                <td className="button-contains">
                    <button type="button"><i className="fa fa-times color-blue" aria-hidden="true" onClick={this.onDelete}></i></button>
                </td>
            </tr>
			)
	}
	onDelete(e) {
    e.preventDefault();
		this.props.onDelete(this.props.id);
	}
	handleChange(event) {
		console.log("handleChange");
      if (event.target.value > 0 && event.target.value <= 1000) {
      	var val = event.target.value / 100;
      	var oldval = this.state.value / 100;
      	var food = this.state.product;
      	var prot = food.proteins;
      	var fat = food.fats;
      	var hid = food.hidrocarbonats;
      	var col = food.colories;
      	food.proteins = ((food.proteins / oldval) * val).toFixed(2);
      	food.fats = ((food.fats / oldval) * val).toFixed(2);
      	food.hidrocarbonats = ((food.hidrocarbonats / oldval) * val).toFixed(2);
      	food.colories = ((food.colories / oldval) * val).toFixed(2);
      	var obj = { proteins: food.proteins-prot, fats: food.fats-fat, hidrocarbonats: food.hidrocarbonats - hid, colories: food.colories - col};
      	this.setState({value: event.target.value, product: food});
      	this.props.onChangeSumm(obj);
      }
  	}
}

class FoodList extends React.Component {
	render() { 

		var items = this.props.food.map(function(item, index) {
  			return (
    			<FoodItem name={item.name} key={index} proteins={item.proteins} fats={item.fats} hidrocarbonats={item.hidrocarbonats} colories={item.colories} id={index}/> 
  			)
		});

		return  <div className = "col-lg-6 fixblock">
        	<div className="editor-column">
            	<div className="excercises">
					<div id="container" ref="container">
						<table ref="table" cellPadding="2" cellSpacing="1" width="100%">
							<thead>
  								<tr>
    								<th className="table-header grocery-list-name">Продукт</th>
                    				<td className="table-header grocery-list-pfc">Б</td>
                    				<td className="table-header grocery-list-pfc">Ж</td>
                    				<td className="table-header grocery-list-pfc">У</td>
                    				<td className="table-header grocery-list-pfc">Ккал</td>
  								</tr>
  							</thead>
  							<tbody>
								{items}					
  							</tbody>
  						</table>
  					</div>
  				</div>
  			</div>
  		</div>;
	}
	
	componentDidMount(){
    	console.log("componentDidMount()");
    	var c = this.refs.container;
		var t = this.refs.table;
		var tH = t.tHead;
		var tB = t.tBodies[0];
		//var tF = t.tFoot;
		var len = t.rows[0].cells.length;

		for(var i = 0; i < len; i++){
		  var w = tH.rows[0].cells[i].offsetWidth + t.cellPadding*2 + "px";
		  tH.rows[0].cells[i].style.width = 
		  tB.rows[0].cells[i].style.width = w;
		  //tF.rows[0].cells[i].style.width = w;
		}
		for (var i = 0; i < tB.rows.length; i++) {
			var w = tH.rows[0].cells[0].offsetWidth + t.cellPadding*2 + "px";
			tB.rows[i].cells[0].style.width = w;
		}
		t.style.width = (t.offsetWidth + t.cellPadding*len) + "px";

		var header = t.cloneNode(false);
		header.appendChild(tH);
		c.parentNode.insertBefore(header, c);

		c.style.height = "70vh";
		c.style.overflow = "auto";
		c.style.width = (c.offsetWidth - c.clientWidth) + t.offsetWidth + "px";
    } 
    /*{
		var c = this.getElementById('container');
		var t = c.firstChild;
		var tH = t.tHead;
		var tB = t.tBodies[0];
		//var tF = t.tFoot;
		var len = t.rows[0].cells.length;

		for(var i = 0; i < len; i++){
		  var w = tH.rows[0].cells[i].offsetWidth + t.cellPadding*2 + "px";
		  tH.rows[0].cells[i].style.width = 
		  tB.rows[0].cells[i].style.width = w;
		  //tF.rows[0].cells[i].style.width = w;
		}
		t.style.width = (t.offsetWidth + t.cellPadding*len) + "px";

		var header = t.cloneNode(false);
		header.appendChild(tH);
		c.parentNode.insertBefore(header, c);

		c.style.height = "70vh";
		c.style.overflow = "auto";
		c.style.width = (c.offsetWidth - c.clientWidth) + t.offsetWidth + "px";
	}*/
}

class FoodItem extends React.Component {
	render() {
		return <tr ref="dragitem" id={this.props.id}>
                    <td>{this.props.name}</td>
                    <td className="grocery-list-pfc">{this.props.proteins}</td>
                    <td className="grocery-list-pfc">{this.props.fats}</td>
                    <td className="grocery-list-pfc">{this.props.hidrocarbonats}</td>
                    <td className="grocery-list-pfc">{this.props.colories}</td>
                </tr>;
	}

	componentDidMount () {
		jQuery(this.refs.dragitem).draggable({ appendTo: 'body', /*helper: 'clone',*/
      helper: function () {
        return $(this).clone().css({width: $(this).width(), backgroundColor: '#dae3ea'});
      }, 
      start: function (event, ui) {
            //$(this).hide();
            $(this).css("opacity", 0);
        },
        stop: function (event, ui) {
            //$(this).show();
            $(this).css("opacity", 100);
        }, 
        revert:  function(dropped) {
             var $draggable = $(this),
                 hasBeenDroppedBefore = $draggable.data('hasBeenDropped'),
                 wasJustDropped = dropped && dropped[0].id == "droppable";
             if(wasJustDropped) {
                 // don't revert, it's in the droppable
                 return false;
             } else {
                 if (hasBeenDroppedBefore) {
                     // don't rely on the built in revert, do it yourself
                     $draggable.animate({ top: 0, left: 0 }, 'slow');
                     return false;
                 } else {
                     // just let the built in revert work, although really, you could animate to 0,0 here as well
                     return true;
                 }
             }
        }
    });
       /*   this.getChildren().forEach(function(child, i) {
            jQuery(this.getDOMNode()).append('<' + this.props.childComponent.componentConstructor.displayName + ' />');
            var node = jQuery(this.getDOMNode()).children().last()[0];
            node.dataset.reactSortablePos = i;
            React.renderComponent(cloneWithProps(child), node);
          }.bind(this));*/
	}
}

class Content extends React.Component {
	render() {
		return <div>
		<NutritiondaysList />
		<FoodList food={foods}/>
		</div>;
	}
}

class HelloMessage extends React.Component {
  render() {
    return <div>Hello {this.props.name}</div>;
  }
}

ReactDOM.render(
  <Content name="John" />,
  document.getElementById('content')
);