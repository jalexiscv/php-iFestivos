# Festivos

Esta clase se genera un array con los calculos necesarios para calcular los festivos de
colombia de acuerdo a la normativa nuestro pais. Los festivos se dividen en 4 tipos de festivos:
Festivos fijos (son aquellos que no se mueven de la fecha sin importar que día de la semana son)
Festivos Emiliani (estos son los festivos que son corridos al lunes siguiente a ocurrir el verdadero día feriado, esto debido a la legislación colombiana)
Festivos calculados por el dia de pascuas.
Festivos calculados por el dia de pascuas y corridos al lunes siguiente por la ley Emiliani.
La clase creada tiene un constructor al cual se le pasa el numero del año del cual se quiere saber las
fechas festivas, las cuales se almacenan en un array de tres dimensiones donde la primer dimencion es
el año, la segunda dimencion es el mes y la tercera dimencion es el dia.
para calcular el dia de pascua se utiliza una funcion nativa de PHP llamada easter_date al cual se le pasa
por argumento el año al cual dicha fecha. Adicional a esto tambien la clase tiene una funcion a la cual se le
pasa por parametro un dia y un mes del año calculado, y esta nos dira si ese dia es festivo.
