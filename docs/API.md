# API FERIADOS BOLIVA

El servicio web es accesible desde la URL raíz:

```sh
http://192.168.20.219:3000/v1/feriados?pais=BO&ano=2016&mes=05
```

## SERVICIO WEB

## Servicio Web: Consulta de feriados en un mes y año especifico.

Este servicio retorna las fechas y nombres de los feridos por mes. 

Si el feriado es un domingo, por Decreto Supremo XXXX, la fecha que se muestra como feriado es la del día siguiente. Ej. El año 2016, el día del trabajo fue un domingo, por lo que el feriado se recorrió un dia, es decir el lunes 2 de mayo.

Cabecera y ruta:

```sh
Content-Type: application/json
GET /v1/feriados?pais=`pais`&ano=`ano`&mes=`mes`&dia=`dia`
```
Parámetros de entrada:

| Tipo           | Parámetro    | Descripción                                                                                                                          |
|----------------|--------------|--------------------------------------------------------------------------------------------------------------------------------------|
| Entrada        | `pais`      | Código de país. Ej. `BO`. Si se desea agregar otro pais. Agreguese en /data, formato `XX.json`                                                                                        |
| Entrada        | `ano`        | Año del que se desea conocer los feriados. Ej. `2016`
| Entrada        | `mes`      |  Mes del que se desea conocer los feriados. Se representa en numero de mes. Ej. `05` |                                                                                   |
| Entrada        | `dia`      |  Día del que se desea conocer si es feriado. Se representa en numero de día. Ej. `01` | 

NOTA: Los parametros `pais` y `ano` son obligatorios, `mes` y `dia` son opcionales.

Ejemplo con `curl`:

Consulta de feriados de Bolivia `BO` año `2016`, mes `05` (mayo) de la siguiente forma:

```sh
curl -i "http://192.168.20.219:3000/v1/feriados?pais=BO&ano=2016&mes=05"
```

### Respuesta en el caso de que la petición sea correcta:

```sh
HTTP/1.1 200 OK
content-type: application/json; charset=utf-8

{
    "status":200,
    "feriados":
        [
            {
                "fecha":"2016-05-02",
                "nombre":"Día del Trabajo"
            },
            {
                "fecha":"2016-05-26",
                "nombre":"Corpus Christi"
            }
         ]
}
```
### Respuesta en el caso de que la petición sea incorrecta:

* Falta parámetro `pais`

```sh
curl -i "http://192.168.20.219:3000/v1/feriados?pais=&ano=2016&mes=05"
```
* Respuesta

```
{
  "status": 400,
  "error": "Se requiere el parametro pais."
}
```

* Falta parámetro `ano`

```sh
curl -i "http://192.168.20.219:3000/v1/feriados?pais=BO&ano=&mes=05"
```
* Respuesta

```
{
  "status": 400,
  "error": "Se require el parametro año."
}
```
