<?php

$root = (!isset($root)) ? "../../" : $root;

/*
 * Copyright (c) 2013, Alexis
 * All rights reserved.
 *
 * Esta clase se genera un array con los calculos necesarios para calcular los festivos de
 * colombia de acuerdo a la normativa nuestro pais. Los festivos se dividen en 4 tipos de festivos:
 * 1. Festivos fijos (son aquellos que no se mueven de la fecha sin importar que día de la semana son)
 * 2. Festivos Emiliani (estos son los festivos que son corridos al lunes siguiente a ocurrir el verdadero día
 *     feriado, esto debido a la legislación colombiana)
 * 3. Festivos calculados por el dia de pascuas.
 * 4. Festivos calculados por el dia de pascuas y corridos al lunes siguiente por la ley Emiliani.
 *
 * La clase creada tiene un constructor al cual se le pasa el numero del año del cual se quiere saber las
 * fechas festivas, las cuales se almacenan en un array de tres dimensiones donde la primer dimencion es
 * el año, la segunda dimencion es el mes y la tercera dimencion es el dia.
 *
 * para calcular el dia de pascua se utiliza una funcion nativa de PHP llamada easter_date al cual se le pasa
 * por argumento el año al cual dicha fecha. Adicional a esto tambien la clase tiene una funcion a la cual se le
 * pasa por parametro un dia y un mes del año calculado, y esta nos dira si ese dia es festivo.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

class Festivos {

  private $hoy;
  private $festivos;
  private $ano;
  private $pascua_mes;
  private $pascua_dia;

  public function getFestivos($ano = '') {
    $this->festivos($ano);
    return $this->festivos;
  }

  public function Festivos($ano = '') {
    $this->hoy = date('d/m/Y');

    if ($ano == '')
      $ano = date('Y');

    $this->ano = $ano;

    $this->pascua_mes = date("m", easter_date($this->ano));
    $this->pascua_dia = date("d", easter_date($this->ano));

    $this->festivos[$ano][1][1] = true;           // Primero de Enero
    $this->festivos[$ano][5][1] = true;           // Dia del Trabajo 1 de Mayo
    $this->festivos[$ano][7][20] = true;           // Independencia 20 de Julio
    $this->festivos[$ano][8][7] = true;           // Batalla de Boyacá 7 de Agosto
    $this->festivos[$ano][12][8] = true;           // Maria Inmaculada 8 diciembre (religiosa)
    $this->festivos[$ano][12][25] = true;           // Navidad 25 de diciembre

    $this->calcula_emiliani(1, 6);                          // Reyes Magos Enero 6
    $this->calcula_emiliani(3, 19);                         // San Jose Marzo 19
    $this->calcula_emiliani(6, 29);                         // San Pedro y San Pablo Junio 29
    $this->calcula_emiliani(8, 15);                         // Asunción Agosto 15
    $this->calcula_emiliani(10, 12);                        // Descubrimiento de América Oct 12
    $this->calcula_emiliani(11, 1);                         // Todos los santos Nov 1
    $this->calcula_emiliani(11, 11);                        // Independencia de Cartagena Nov 11
//otras fechas calculadas a partir de la pascua.

    $this->otrasFechasCalculadas(-3);                       //jueves santo
    $this->otrasFechasCalculadas(-2);                       //viernes santo

    $this->otrasFechasCalculadas(43, true);          //Ascención el Señor pascua
    $this->otrasFechasCalculadas(64, true);          //Corpus Cristi
    $this->otrasFechasCalculadas(71, true);          //Sagrado Corazón
// otras fechas importantes que no son festivos
// $this->otrasFechasCalculadas(-46);           // Miércoles de Ceniza
// $this->otrasFechasCalculadas(-46);           // Miércoles de Ceniza
// $this->otrasFechasCalculadas(-48);           // Lunes de Carnaval Barranquilla
// $this->otrasFechasCalculadas(-47);           // Martes de Carnaval Barranquilla
  }

  protected function calcula_emiliani($mes_festivo, $dia_festivo) {
// funcion que mueve una fecha diferente a lunes al siguiente lunes en el
// calendario y se aplica a fechas que estan bajo la ley emiliani
//global  $y,$dia_festivo,$mes_festivo,$festivo;
// Extrae el dia de la semana
// 0 Domingo  6 Sábado
    $dd = date("w", mktime(0, 0, 0, $mes_festivo, $dia_festivo, $this->ano));
    switch ($dd) {
      case 0:                                    // Domingo
        $dia_festivo = $dia_festivo + 1;
        break;
      case 2:                                    // Martes.
        $dia_festivo = $dia_festivo + 6;
        break;
      case 3:                                    // Miércoles
        $dia_festivo = $dia_festivo + 5;
        break;
      case 4:                                     // Jueves
        $dia_festivo = $dia_festivo + 4;
        break;
      case 5:                                     // Viernes
        $dia_festivo = $dia_festivo + 3;
        break;
      case 6:                                     // Sábado
        $dia_festivo = $dia_festivo + 2;
        break;
    }
    $mes = date("n", mktime(0, 0, 0, $mes_festivo, $dia_festivo, $this->ano)) + 0;
    $dia = date("d", mktime(0, 0, 0, $mes_festivo, $dia_festivo, $this->ano)) + 0;
    $this->festivos[$this->ano][$mes][$dia] = true;
  }

  protected function otrasFechasCalculadas($cantidadDias = 0, $siguienteLunes = false) {
    $mes_festivo = date("n", mktime(0, 0, 0, $this->pascua_mes, $this->pascua_dia + $cantidadDias, $this->ano));
    $dia_festivo = date("d", mktime(0, 0, 0, $this->pascua_mes, $this->pascua_dia + $cantidadDias, $this->ano));

    if ($siguienteLunes) {
      $this->calcula_emiliani($mes_festivo, $dia_festivo);
    } else {
      $this->festivos[$this->ano][$mes_festivo + 0][$dia_festivo + 0] = true;
    }
  }

  /**
   * Verifica si un dia en un mes especificado es dia festivo
   * @param type $dia
   * @param type $mes
   * @return boolean
   */
  public function es_festivo($dia, $mes) {
//echo (int)$mes;
    if ($dia == '' or $mes == '') {
      return false;
    }

    if (isset($this->festivos[$this->ano][(int) $mes][(int) $dia])) {
      return(true);
    } else {
      return(false);
    }
  }

  /**
   * Metodo getDiasHabiles
   * Permite devolver un arreglo con los dias habiles
   * entre el rango de fechas dado excluyendo los
   * dias feriados dados (Si existen)
   * @param string $fechainicio Fecha de inicio en formato Y-m-d
   * @param string $fechafin Fecha de fin en formato Y-m-d
   * @param array $diasferiados Arreglo de dias feriados en formato Y-m-d
   * @return array $diashabiles Arreglo definitivo de dias habiles
   */
  function getDiasHabiles($fechainicio, $fechafin, $diasferiados = array()) {
// Convirtiendo en timestamp las fechas
    $fechainicio = strtotime($fechainicio);
    $fechafin = strtotime($fechafin);

// Incremento en 1 dia
    $incremento = 24 * 60 * 60;

// Arreglo de dias habiles, inicianlizacion
    $diashabiles = array();

// Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
    for ($midia = $fechainicio; $midia <= $fechafin; $midia += $incremento) {
// Si el dia indicado, no es sabado o domingo es habil
      if (!in_array(date('N', $midia), array(6, 7))) { // DOC: http://www.php.net/manual/es/function.date.php
// Si no es un dia feriado entonces es habil
        if (!in_array(date('Y-m-d', $midia), $diasferiados)) {
          array_push($diashabiles, date('Y-m-d', $midia));
        }
      }
    }

    return $diashabiles;
  }

  /**
   * Calcula los dias habiles existentes entre dos fechas
   * @param type $inicial
   * @param type $final
   */
  function habiles($inicial, $final) {
    $fecha["inicial"] = strtotime($inicial);
    $fecha["final"] = strtotime($final);
    $incremento = 24 * 60 * 60;
    for ($dia = $fecha["inicial"]; $dia <= $fecha["final"]; $dia += $incremento) {
      echo("\n" . date('Y-m-d', $dia));
    }
  }

  /**
   * Este metodo retorna un vetor que contiene el listado de todos los dias festivos de un año especifico
   * * */
  function listado_festivos($annno) {
    $listado = $this->getFestivos($annno);
    $festivos = array();
    foreach ($listado as $anno => $meses) {
      foreach ($meses as $mes => $dias) {
        foreach ($dias as $dia => $numero) {
          $fecha = strtotime($anno . "-" . $mes . "-" . $dia);
          array_push($festivos, date('Y-m-d', $fecha));
        }
      }
    }
    return($festivos);
  }

  function dias_trascurridos($inicial, $final) {
    $inicial = new DateTime($inicial);
    $final = new DateTime($final);
    $interval = $inicial->diff($final);
    $trascurrido['dias'] = $interval->d;
    $trascurrido['meses'] = $interval->m;
    $trascurrido['annos'] = $interval->y;
    $trascurrido['expresion'] = $trascurrido['dias'] . ":" . $trascurrido['meses'] . ":" . $trascurrido['annos'];
    return($trascurrido);
  }

  function dias_habiles($inicial, $final) {
    $festivos = $this->listado_festivos(date("Y"));
    $fechainicio = strtotime($inicial);
    $fechafin = strtotime($final);
    $incremento = 24 * 60 * 60;
    $diashabiles = array();
    for ($midia = $fechainicio; $midia <= $fechafin; $midia += $incremento) {
      if (!in_array(date('N', $midia), array(6, 7))) {
        if (!in_array(date('Y-m-d', $midia), $festivos)) {
          array_push($diashabiles, date('Y-m-d', $midia));
        }
      }
    }
    $diashabiles['conteo'] = count($diashabiles);
    return($diashabiles);
  }

  function festivos_trascurridos($inicial, $final) {
    $f = array();
    $f['inicial'] = $inicial;
    $f['final'] = $final;
    $festivos = $this->listado_festivos(date("Y"));
    $trascurridos = 0;
    $incremento = 24 * 60 * 60;
    $f['inicial'] = strtotime($f['inicial']);
    $f['final'] = strtotime($f['final']);
    for ($dia = $f["inicial"]; $dia <= $f["final"]; $dia += $incremento) {
      $d = date('Y-m-d', $dia);
      foreach ($festivos as $clave => $valor) {
        if ($d == $valor) {
          $trascurridos++;
        }
      }
    }
    return($trascurridos);
  }

}

?>
