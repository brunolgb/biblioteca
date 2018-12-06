function nossa(){
	var x = document.getElementById('muda');
	x.style.display='none';
}
function muda($id,$valor){
	document.getElementById($id).innerHTML= $valor;
}
function mudando($idEmprestado,$id1,$id2,$id3,$id4,$valor1,$valor2,$valor3,$valor4,$ent)
{
	var verific = document.getElementById('verifAlt');
	var idRec1 = document.getElementById($id1);
	var idRec2 = document.getElementById($id2);
	var idRec3 = document.getElementById($id3);
	var idRec4 = document.getElementById($id4);
	var idRecebeTexto = document.getElementById("alteracao");
	var entregue_input = document.getElementById('entregue_input');
	var inputEnt = document.getElementById('ent');


	// colocando os valores nos inputs
	entregue_input.style.display = 'block';
	inputEnt.value=$ent;
	verific.value = $idEmprestado;
	idRec1.value= $valor1;
	idRec2.value= $valor2;
	idRec3.value= $valor3;
	idRec4.value= $valor4;
	idRecebeTexto.style.opacity = '1';
	idRecebeTexto.innerHTML = "- Estamos alterando o registro "+$idEmprestado;
}
function carreFocus($vfo)
{
	var rfocus = document.getElementById($vfo);
	rfocus.focus();
}

function enviar($filtro)
{
	var formulario = document.getElementById($filtro);
	formulario.submit();
}
function total($idMuda,$total)
{
	var idm = document.getElementById($idMuda);
	idm.innerHTML = $total;
}

function relatorio()
{
	var botao = document.getElementById('livroLido');
	var relatorio = document.getElementsByClassName('relatorio')[0];
	botao.addEventListener('click',blocoLivroLido);

	// funcao para abrir
	function blocoLivroLido()
	{
		relatorio.classList.toggle('relatorioLivroLido');
	}
}