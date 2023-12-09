<?php

/**
 * Clase que me permitirá llevar un registro de los errores que se producen
 */
class GestorErrores
{
    /**
     * Lista en la que guardamos los errores. Solo se podrá almacenar una descripción
     * por campo
     * @var array
     */
    private $errores = array();

    private $format_prefix;
    private $format_suffix;

    /**
     * Crea el gestor de errores y anota las etiquetas que se muestran antes
     * y después en caso de queramos salida formateada
     * @param type $format_prefix
     * @param type $format_sufix
     */
    public function __construct($format_prefix = '', $format_sufix = '')
    {
        $this->format_prefix = $format_prefix;
        $this->format_suffix = $format_sufix;
    }

    /**
     * Anota un error para un campo en nuestro gestor de errores
     * @param type $campo
     * @param type $descripcion
     */
    public function AnotaError(string $campo, string $descripcion)
    {
        $this->errores[$campo] = $descripcion;
    }

    /**
     * Indica si hay errores
     * @return boolean
     */
    public function HayErrores()
    {
        return count($this->errores) > 0;
    }

    public function getErrores()
    {
        return $this->errores;
    }
    /**
     * Indica si hay error en un campo
     * @return boolean
     */
    public function HayError($campo)
    {
        return isset($this->errores[$campo]);
    }

    /**
     * Devuelve la descripción de error para un campo o una cadaena vacia si no
     * hay
     * @param type $campo
     * @return string
     */
    public function Error($campo)
    {
        if (isset($this->errores[$campo])) {
            return $this->errores[$campo];
        } else {
            return '';
        }
    }

    /**
     * Devuelve la descripción del error o cadena vacia si no hay
     * @param type $campo
     * @return string
     */
    public function ErrorFormateado($campo)
    {
        if ($this->HayError($campo)) {
            return $this->format_prefix . $this->Error($campo) . $this->format_suffix;
        } else {
            return '';
        }
    }
    /**
     * Validar un correo electrónico.
     *
     * @param string $campo Campo asociado al correo electrónico.
     * @param string $mail Dirección de correo electrónico a validar.
     */
    public function validateMail($campo, $mail)
    {
        $mail = htmlspecialchars($mail);
        stripslashes($mail);

        if (!filter_var(trim($mail), FILTER_VALIDATE_EMAIL)) {
            $this->AnotaError($campo, "Correo electrónico no válido");
        }
    }

    /**
     * Validar un NIF (Número de Identificación Fiscal).
     *
     * @param string $campo Campo asociado al NIF.
     * @param string $nif NIF a validar.
     */
    function validateNif($campo, $nif)
    {
        $nif = filter_var($nif, FILTER_SANITIZE_SPECIAL_CHARS);
        stripslashes($nif);
        if (preg_match("/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i", $nif)) {

            $numero = substr($nif, 0, 8);
            $letra = strtoupper(substr($nif, 8, 1));

            $letrasValidas = "TRWAGMYFPDXBNJZSQVHLCKE";
            $indice = $numero % 23;
            $letraEsperada = $letrasValidas[$indice];
            if ($letra != $letraEsperada) {
                $this->AnotaError($campo, "NIF no válido");
            };
        } else {
            $this->AnotaError($campo, "NIF no válido");
        }
    }

    /**
     * Validar una fecha.
     *
     * @param string $campo Campo asociado a la fecha.
     * @param string $date Fecha a validar.
     */
    public function validateDate($campo, $date)
    {
        $taskDate = new DateTime($date);
        $currentDate = new DateTime();

        if ($taskDate < $currentDate) {
            $this->AnotaError($campo, "Fecha no válida");
        }
    }

    /**
     * Validar un código postal.
     *
     * @param string $campo Campo asociado al código postal.
     * @param string $valor Código postal a validar.
     * @param string $provincia Provincia asociada al código postal.
     */
    public function validatePostalCode($campo, $valor, $provincia)
    {
        if (substr($valor, 0, 2) != $provincia || !preg_match("/^[0-9]{5}$/", trim($valor))) {
            $this->AnotaError($campo, "Debe introducir un código postal válido para su provincia");
        }
    }

    /**
     * Validar un número de teléfono.
     *
     * @param string $campo Campo asociado al número de teléfono.
     * @param string $valor Número de teléfono a validar.
     */
    public function validatePhone($campo, $valor)
    {
        if (!preg_match('/^(6|7)\d{8}$/', $valor)) {
            $this->AnotaError("tel", "Teléfono incorrecto");
        }
    }

    /**
     * Validar un tipo de imagen permitido.
     *
     * @param string $valor Tipo de imagen a validar.
     */
    public function validateImg($valor)
    {
        $allowedMimeType = array('image/jpeg', 'image/png', 'image/gif');
        if (!in_array($valor, $allowedMimeType)) {
            $this->AnotaError('imagen', 'Debe introducir una imagen');
        }
    }

    /**
     * Validar un tipo de documento de texto permitido.
     *
     * @param string $valor Tipo de documento de texto a validar.
     */
    public function validateTextDoc($valor)
    {
        $allowedMimeType = array(
            'text/plain',
            'application/vnd.oasis.opendocument.text',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/pdf'
        );
        if (!in_array($valor, $allowedMimeType)) {
            $this->AnotaError('txt', 'Debe introducir un documento de texto');
        }
    }

    /**
     * Verificar si un campo de entrada está vacío.
     *
     * @param string $campo Campo asociado al valor.
     * @param string $valor Valor a verificar.
     */
    public function emptyInput($campo, $valor)
    {
        $valor = htmlspecialchars($valor);

        if (empty($valor)) {
            $this->AnotaError($campo, "Este campo es obligatorio");
        }
    }

    /**
     * Validar un rol.
     *
     * @param string $valor Rol a validar.
     */
    function validateRol($valor)
    {
        if ($valor !== '0' && $valor !== '1') {
            $this->AnotaError('rol', "Este campo es obligatorio");
        }
    }
}
