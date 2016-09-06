//отправка запроса AJAX
function sendAjax(data, opt) { //data = объект отправляемых данных opt = объект с настройками запроса
    //console.log(data);
    //console.log(opt);
    if (opt === undefined) {
        opt = {url: 'index.php',
               data: data,
               dataType: 'json'};
    }
    return $.post(opt);
}

/*обрабатываем смену родителя (для форм добавления и редактирования элементов)
* select = ссылка на измененный элемент, id формы храниться в атрибуте data-form-id
*/
function handlerChangePath(select){
    //console.log(select);
    //подготовка select'ов для добавления нового (ставим правильные атрибуты, удаляем ненужные select'ы)
    if (select.id < select.parentElement.childElementCount - 1){//Если пользователь меняет "предыдущий" уровень, чистим все ненужные select'ы, и привязываем измененный select к форме (атрибут form)
        clearSelects(select.id, select.parentElement);
        select.setAttribute('form', select.dataset.formId)
    } else { //иначе, пользователь меняет "текущий" уровень, нам нужно привязать "текущий" select к форме и отвязать "предыдущий" от нашей формы
        select.setAttribute('form', select.dataset.formId);
        if (select.previousElementSibling&&select.previousElementSibling.getAttribute('form')){ //если есть предыдущий select и в нем задан атрибут form, удаляем атрибут form
            select.previousElementSibling.removeAttribute('form');
        }
    }
    var data = {parent_id:select.options[select.selectedIndex].value, caller_name:select.name}; //готовим данные к отправке
    sendAjax(data).done(function (data) {
        showNextLevelSelect(data, select.parentElement, select.dataset.formId, select.name);
    });
}

//настройка и добавление нового select
function showNextLevelSelect(data, container, form_id, name){
    //console.log(data);
    //console.log(container);
    //console.log(form_id);
    //console.log(name);
    if (data){
        var attr = new Array;
        attr[0] = ['id', container.childElementCount];
        attr[1] = ['class', 'col-xs-2 col-xs-offset-1'];
        attr[2] = ['data-form-id', form_id];
        attr[3] = ['name', name];
        var opt = new Array;
        for (var id in data){
            opt[opt.length] = [id, data[id], false, false];
        }
        var next_select = createSelect(attr, opt, container.childElementCount);
        parents.appendChild(next_select);
        next_select.addEventListener('change', function(){handlerChangePath(this)});
    }
}

//создаем select
function createSelect(attr, opt, level){ //attr массив атрибутов,opt массив опций
    //console.log(attr);
    //console.log(opt);
    var new_select = document.createElement('select');
    attr.forEach(function(item){
        new_select.setAttribute(item[0], item[1]);
    });
    opt.unshift(['', level+' уровень']); //первый эллемент каждого selecta
    opt.forEach(function(item, i){
        var new_option = new Option (item[1], item[0]);
        new_select.appendChild(new_option);
    });
    new_select.options[0].disabled = true;
    return new_select;
}

//удаление select'ов если пользователь меняет предыдущие группы
function clearSelects(id, parent){
    //console.log(id);
    //console.log(parent);
    for (var i = parent.childElementCount - 1; i>id; i--){ //удаляем все select'ы id которых больше чем id текущего
        parent.removeChild(parent.children[i]);
    }
}

//отправляем данные формы на добавление категории
function handlerSubmitAddForm(form) {
    //console.log(form);
    var name = form.elements.catName.value;
    var level = form.elements.setparent.id;
    var parent_id = form.elements.setparent.options[form.elements.setparent.selectedIndex].value;
    var caller_name = form.name;
    var data = {name:name, parent_id:parent_id, level:++level, caller_name:caller_name};
    sendAjax(data).done(function(){alert('Категория добавлена в базу. \n Для того что бы увидеть ее в структуре, обновите базу')});
}