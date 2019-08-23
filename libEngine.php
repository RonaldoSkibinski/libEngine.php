<?php

//----------------------------------------------------------------------------------------------------------------------------

//EXEMPLODE CLASSE PARA CRIAR BANCO DE DADOS E ALGUMAS FUNÇÕES EXTRAS
  class banco {
      public $res;
      public $cnx;
      public $cheader;
      
      //FUNÇÃO QUE CONSTROI O BANCO DE DADOS
      function __construct() {
          $this->cnx =pg_connect("host=localhost dbname=sistemaloja user=postgres password=postgre");
      }
      
      //FUNÇÃO DE CONSULTA NO BANCO DE DADOS QUE IRA RETORNAR O RESULTADO NO $THIS->RES
      function consulta($sql) {
          $this->res =pg_query($sql);  
      }
      
      //FUNÇÃO QUE CRIA UMA TABELA COM OS DADOS DA CONSULTA, E A MOLDA CONFORME OS PARAMETROS PASSADOS A ELA, APLICANDO UM DATATABLE AO FINAL
      function lista($name='tabela',$inc='',$alt='',$exc='',$img='') {
          //$ALT E $EXC SÃO OS HREFS PASSADOS QUE AO CLICAR NOS BOTOES ALTERAR E EXCLUIR IRÃO EXECUTAR UMA AÇÃO (PODE SER UTILIZADO EM AJAX)
          //$INC É O HREF DO BOTÃO DE INCLUSÃO
          //CODIGOS ESSES QUE PODEM SER PASSADOS PARA EXECUTAR VARIAS FUNÇÕES NA PROPRIA LIB MESMO
          //Botão de Início
          echo ("<div style='width:100px;
                          height:25px; width: 65px; color:black; background-color:rgba(255,255,255,1); 
                          font-family: Arial Black, Times, serif; border-radius:7px; 
                          border-color: white; text-align: center;'><a href='principal.php'>Inicio</a></div>");
          if($inc) echo("<center><p  style='width:100px;
                          height:25px;color:white; background-color:rgba(255,255,255,1); 
                          font-family: Arial Black, Times, serif; border-radius:7px; 
                          border-color: white, text-align: center;' align=\"center\"><a href=\"$inc\">Incluir</a></p></center>");
          echo("<table id='$name' class='display' cellspacing='0' width='100%'>");
          echo("<thead><tr>");
          for($c=0; $c < count($this->cheader); $c++){
              echo("<th>".$this->cheader[$c]."</th>");
          }
          echo("</tr></thead><tbody>");
          $n = pg_num_fields($this->res);
          $codigo = @pg_field_name($this->res, 0);
          while($line=@pg_fetch_object($this->res)){
              echo("<tr>");
              for($c=0; $c<$n;$c++){ 
                  $campo=@pg_field_name($this->res,$c);
                  echo("<td>".$line->$campo."</td>");
              }
              if($alt or $exc){
                  echo("<td align=center>");
                  if($alt) echo("<a href=\"$alt?alt=".$line->$codigo."\">Alterar</a> ");
                  if($exc) echo("<a href=\"$exc?exc=".$line->$codigo."\">Excluir</a> ");
                  echo("</td>");
              }   
              echo("</tr>");
          }        
          echo("</tbody></table>");
          echo("  <script> $(document).ready(function() {
                      $('#$name').DataTable();
                      } );
              </script>");
    }
      
      //FUNÇÃO QUE CRIA UM COMBOBOX COM OS RESULTADOS RETORNADO PELA CONSULTA SQL
      function combobox($nome, $id=0){
          echo("<br><select name=$nome>");
          while($line=@pg_fetch_object($this->res)){
              $codigo = @pg_field_name($this->res,0);
              $nome = @pg_field_name($this->res,1);
              echo("<option value='".$line->$codigo."'>".$line->$nome."</option>");
          }        
          echo("</select>");  
      }
  }
  //----------------------------------------------------------------------------------------------------------------------------

    //EXEMPLO DE FUNÇÕES PARA CRIAR PAGINAS COM MAIS AGILIDADE

    //EXEMPLO DE FUNÇÃO QUE CRIA UM CABEÇALHO
    function head(){
        echo("
        <html>
            <head>
                <title></title>
            </head>
                <link rel='stylesheet' href='../DT/media/css/jquery.dataTables.min.css'>
                <link rel=\"stylesheet\" href=\"../css/css.css\">
            <body>");
    }

    //EXELMPO DE FUNÇÃO QUE CRIA UM FOOTER
    function foot(){
        echo("</body></html>");
    }
  //----------------------------------------------------------------------------------------------------------------------------

    //CLASSE PARA CRIAÇÃO DE FORMULARIO E ALGUMAS FUNÇÕES EXTRAS
    Class form{
        public $campo=array();
        public $titulo, $method='POST',$action='';
        function __construct($titulo='Formulário',$action='',$method=''){
            $this->titulo=$titulo;
            $this->action=$action;
            $this->method=$method;
        }
        
        //CRIA UM INPUT TEXT NO FORMULARIO
        function text($name='obj',$caption='Campo',$size=30,$maxlenght='',$obrig=''){
            $this->campo[$name]['type']='text';
            $this->campo[$name]['name']=$name;
            $this->campo[$name]['caption']=$caption;
            $this->campo[$name]['size']=$size;
            $this->campo[$name]['maxlenght']=$maxlenght;
            $this->campo[$name]['obrig']=$obrig;
        }
        
        //CRIA UM INPUT TIME NO FORMULARIO
        function time($name='obj',$caption='Campo',$obrig=''){
            $this->campo[$name]['type']='time';
            $this->campo[$name]['name']=$name;
            $this->campo[$name]['caption']=$caption;
            $this->campo[$name]['obrig']=$obrig;
        }
        
        //CRIA UM INPUT DATE NO FORMULARIO
        function date($name='obj',$caption='Campo',$obrig=''){
            $this->campo[$name]['type']='date';
            $this->campo[$name]['name']=$name;
            $this->campo[$name]['caption']=$caption;
            $this->campo[$name]['obrig']=$obrig;
        }
        
        //CRIA UM INPUT OCULTO
        function hidden($name='obj',$value=''){
            $this->campo[$name]['type']='hidden';
            $this->campo[$name]['name']=$name;
            $this->campo[$name]['value']=$value;
        }
        
        //CRIA UM INPUT PASSWORD
        function password($name='obj',$caption='Senha',$size=30,$maxlenght='',$obrig=''){
            $this->campo[$name]['type']='password';
            $this->campo[$name]['name']=$name;
            $this->campo[$name]['caption']=$caption;
            $this->campo[$name]['size']=$size;
            $this->campo[$name]['maxlenght']=$maxlenght;
            $this->campo[$name]['obrig']=$obrig;
        }
        
        //CRIA UM SELECT NO FORMULARIO
        function select($name='obj',$caption='Selecione',$options=array('selecione'),$obrig=''){
            $this->campo[$name]['type']='select';
            $this->campo[$name]['name']=$name;
            $this->campo[$name]['caption']=$caption;
            $this->campo[$name]['options']=$options;
            $this->campo[$name]['obrig']=$obrig;
        }
        
        //CRIA UM SELECT COM INFORMÇÃOS DA CONSULTA DO BANCO DE DADOS
        function dbselect($name='obj',$caption='Selecione',$res,$obrig=''){
            $this->campo[$name]['type']='dbselect';
            $this->campo[$name]['name']=$name;
            $this->campo[$name]['caption']=$caption;
            $campo1=@pg_field_name($res,0);
            $campo2=@pg_field_name($res,1);
            while($reg=@pg_fetch_object($res)){
                $options_id[]=$reg->$campo1;
                $options_dt[]=$reg->$campo2;
            }
            $this->campo[$name]['options_id']=$options_id;
            $this->campo[$name]['options_dt']=$options_dt;
            $this->campo[$name]['obrig']=$obrig;
        }
        
        //CRIA UM CAMPO RADIO NO FORMULARIO
        function radio($name='obj',$caption='Selecione',$options=array('selecione')){
            $this->campo[$name]['type']='radio';
            $this->campo[$name]['name']=$name;
            $this->campo[$name]['caption']=$caption;
            $this->campo[$name]['options']=$options;
        }
        
        //CRIA UM CAMPO IMAGEM NO FORMULARIO
        function imagem($name='obj',$caption='Campo',$obrig=''){
            $this->campo[$name]['type']='file';
            $this->campo[$name]['name']=$name;
            $this->campo[$name]['caption']=$caption;
            $this->campo[$name]['obrig']=$obrig;
        }
        
        //BUSCA AS INFORMAÇÕES NO BANCO DE DADOS E PREENCHE OS CAMPOS
        function carrega($reg){
            foreach(array_keys($this->campo) as $key){
                @$this->campo[$key]['value']=$reg->$key;
            }   
        }
        
        //MOSTRA O FORMULARIO COMPLETO NA PAGINA
        function show($name='frm', $pag='',$met='', $acao=''){
            echo("<script language=\"javascript\">");
            echo("   function valida$name(){ \n");
            echo("      var erro='';\n");
            foreach(array_keys($this->campo) as $key){
                if(@$this->campo[$key]['obrig']!=''){
                    echo("      if(document.$name.".$this->campo[$key]['name'].".value==''){erro+='Verifique o campo \"".$this->campo[$key]['caption']."\" preenchido incorretamente...\\n';}\n");
                }
            }
            echo("      if (erro==''){if (confirm('Confirma os dados digitados?')){document.$name.submit();}}else{alert(erro);}\n");
            echo("   }\n");
            echo("</script>");   
            echo("<form name=\"$name\" id=\"$name\" action=\"".$pag.".php?x=".$acao."\" method=\"".$met."\" enctype=\"multipart/form-data\">\n");
            echo("<table border=\"0\" align=\"center\" width=\"90%\">\n");
            echo("<tr><th align=\"center\" colspan=2><b>$this->titulo</b></th></tr>\n");
            foreach(array_keys($this->campo) as $key){
                if(@$this->campo[$key]['maxlenght']){$maxlenght=" maxlenght=\"".$this->campo[$key]['maxlenght']."\" ";}else{$maxlenght='';}
                    if($this->campo[$key]['type']=='text'){
                        echo("<tr><td align=\"right\"><b>".$this->campo[$key]['caption']." : </b></td><td><input type=\"".$this->campo[$key]['type']."\" name=\"".$this->campo[$key]['name']."\" size=\"".$this->campo[$key]['size']."\" $maxlenght value=\"".@$this->campo[$key]['value']."\"></td></tr>\n");
                    }elseif($this->campo[$key]['type']=='file'){
                        echo("<tr><td align=\"right\"><b>".$this->campo[$key]['caption']." : </b></td><td><input type=\"".$this->campo[$key]['type']."\" name=\"".$this->campo[$key]['name']."\" ></td></tr>\n");
                    }elseif($this->campo[$key]['type']=='select'){
                        echo("<tr><td align=\"right\"><b>".$this->campo[$key]['caption']." : </b></td><td><select name=\"".$this->campo[$key]['name']."\">\n");
                        echo("<option value=''> Selecione</option>\n");
                        for($c=0;$c<count($this->campo[$key]['options']);$c++){
                            if($this->campo[$key]['value']==$this->campo[$key]['options'][$c]){$sel="selected";}else{$sel="";}
                                echo("<option value=\"".$this->campo[$key]['options'][$c]."\">".$this->campo[$key]['options'][$c]."</option>\n");
                            }
                        echo("</select></td></tr>\n");
                    }elseif($this->campo[$key]['type']=='time'){
                        echo("<tr><td align=\"right\"><b>".$this->campo[$key]['caption']." : </b></td><td><input type=\"".$this->campo[$key]['type']."\" name=\"".$this->campo[$key]['name']."\" value=\"".@$this->campo[$key]['value']."\"></td></tr>\n");
                    }elseif($this->campo[$key]['type']=='date'){
                        echo("<tr><td align=\"right\"><b>".$this->campo[$key]['caption']." : </b></td><td><input type=\"".$this->campo[$key]['type']."\" name=\"".$this->campo[$key]['name']."\" value=\"".@$this->campo[$key]['value']."\"></td></tr>\n");
                    }elseif($this->campo[$key]['type']=='dbselect'){
                        echo("<tr><td align=\"right\"><b>".$this->campo[$key]['caption']." : </b></td><td><select name=\"".$this->campo[$key]['name']."\">\n");
                        echo("<option value=''> Selecione</option>\n");
                        for($c=0;$c<count($this->campo[$key]['options_id']);$c++){
                            if($this->campo[$key]['value']==$this->campo[$key]['options_id'][$c]){$sel="selected";}else{$sel="";}
                            echo("<option $sel value=\"".$this->campo[$key]['options_id'][$c]."\">".$this->campo[$key]['options_dt'][$c]."</option>\n");
                        }
                    echo("</select></td></tr>\n");
                    }elseif($this->campo[$key]['type']=='radio'){
                        echo("<tr><td align=\"right\"><b>".$this->campo[$key]['caption']." : </b></td><td>\n");
                        for($c=0;$c<count($this->campo[$key]['options']);$c++){
                            if(@$this->campo[$key]['value']==$this->campo[$key]['options'][$c]){$sel="selected";}else{$sel="";}
                            echo("<input type=\"radio\" name=\"".$this->campo[$key]['name']."\" value=\"".$this->campo[$key]['options'][$c]."\"> ".$this->campo[$key]['options'][$c]."<br>\n");
                        }
                        echo("</td></tr>\n");
                    }elseif($this->campo[$key]['type']=='password'){
                        echo("<tr><td align=\"right\"><b>".$this->campo[$key]['caption']." : </b></td><td><input type=\"".$this->campo[$key]['type']."\" name=\"".$this->campo[$key]['name']."\" size=\"".$this->campo[$key]['size']."\" $maxlenght></td></tr>\n");
                    }elseif($this->campo[$key]['type']=='hidden'){
                        echo("<input type=\"".$this->campo[$key]['type']."\" name=\"".$this->campo[$key]['name']."\" value=\"".$this->campo[$key]['value']."\">\n");
                    }     
            }
            if($pag==''){
                echo("<tr><th align=\"center\" colspan=2><input type=\"button\" id=\"salvaai\" value=\"SALVAR\" onclick=\"valida$name()\"></th></tr>\n");
            }else{
                echo("<tr><th align=\"center\" colspan=2><input type=\"submit\" id=\"salvaai\" value=\"SALVAR\" \"></th></tr>\n");
            }   
            echo("</table>\n</form>\n");
      }
  }
