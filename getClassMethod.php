<?

// ветка создания конроллера 
// попытка #2

$methods = array();
foreach (get_declared_classes() as $class) 
{    $r = new ReflectionClass($class);   
     // Исключение встроенных классов
     if ($r->isUserDefined()) {      
           foreach ($r->getMethods() as $method) {
                // Eliminate inherited methods
                if ($method->getDeclaringClass()->getName() == $class) { 
                     $signature = "$class::" . $method->getName();               
                     $methods[$signature] = $method;  
                }
            } 
    }
 }



// Затем добавляются функции
$functions = array();
$defined_functions = get_defined_functions();
foreach ($defined_functions['user'] as $function) {  
      $functions[$function] = new ReflectionFunction($function);
    }

// Методы сортируются в алфавитном порядке по классам
function sort_methods($a, $b) { 
       list ($a_class, $a_method) = explode('::', $a);  
         list ($b_class, $b_method) = explode('::', $b);    
         if ($cmp = strcasecmp($a_class, $b_class)) {        
             return $cmp;    }    
             return strcasecmp($a_method, $b_method);
}

uksort($methods, 'sort_methods');
// Алфавитная сортировка функций.
// Из списка нужно исключить функцию сортировки.
unset($functions['sort_methods']);
// Сортировкаksort($functions);

// Вывод информации
foreach (array_merge($functions, $methods) as $name => $reflect) {
       $file = $reflect->getFileName(); 
       $line = $reflect->getStartLine();    
       printf ("%-25s | %-40s | %6d\n", "$name()", $file, $line);
    }

?>