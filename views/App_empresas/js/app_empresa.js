// Animaciones suaves al cargar
	document.addEventListener('DOMContentLoaded', function() {
		const statsCards = document.querySelectorAll('.stats-card');
		statsCards.forEach((card, index) => {
			card.style.opacity = '0';
			card.style.transform = 'translateY(30px)';

			setTimeout(() => {
				card.style.transition = 'all 0.5s ease';
				card.style.opacity = '1';
				card.style.transform = 'translateY(0)';
			}, index * 150);
		});

		// Validaciones del modal
		const form = document.getElementById('formNuevaEmpresa');
		if (form) {
			const inputs = form.querySelectorAll('input[required]');

			inputs.forEach(input => {
				input.addEventListener('blur', function() {
					validateField(this);
				});

				input.addEventListener('input', function() {
					if (this.classList.contains('is-invalid')) {
						validateField(this);
					}
				});
			});

			document.getElementById('empresaEmail').addEventListener('input', function() {
				validateEmail(this);
			});

			document.getElementById('empresaTelefono').addEventListener('input', function() {
				validatePhone(this);
			});

			document.getElementById('empresaNit').addEventListener('input', function() {
				validateNIT(this);
			});
		}

		const formEdi = document.getElementById('formVerEditarEmpresa');
		if (formEdi) {
			const inputs = formEdi.querySelectorAll('input[required]');

			inputs.forEach(input => {
				input.addEventListener('blur', function() {
					validateField(this);
				});

				input.addEventListener('input', function() {
					if (this.classList.contains('is-invalid')) {
						validateField(this);
					}
				});
			});

			document.getElementById('editEmpresaEmail').addEventListener('input', function() {
				validateEmail(this);
			});

			document.getElementById('editEmpresaTelefono').addEventListener('input', function() {
				validatePhone(this);
			});

			document.getElementById('editEmpresaNit').addEventListener('input', function() {
				validateNIT(this);
			});
		}
		
		// Event listeners para filtros
		document.getElementById('shareempresa').addEventListener('input', filtrarEmpresas);
		document.getElementById('estadoempresa').addEventListener('change', filtrarEmpresas);
		
		// Cargar empresas al iniciar
		cargarEmpresas();

	});


	// FUNCIONES DE VALIDACI√ìN
	function validateField(field) {
		const value = field.value.trim();

		if (value === '') {
			setFieldError(field, 'Este campo es obligatorio');
			return false;
		} else {
			setFieldSuccess(field);
			return true;
		}
	}

	function validateEmail(field) {
		const value = field.value.trim();
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

		if (value === '') {
			setFieldError(field, 'Este campo es obligatorio');
			return false;
		} else if (!emailRegex.test(value)) {
			setFieldError(field, 'Ingrese un email v√°lido');
			return false;
		} else {
			setFieldSuccess(field);
			return true;
		}
	}

	function validatePhone(field) {
		const value = field.value.trim();
		const phoneRegex = /^[0-9]{7,15}$/;

		if (value === '') {
			setFieldError(field, 'Este campo es obligatorio');
			return false;
		} else if (!phoneRegex.test(value)) {
			setFieldError(field, 'Ingrese un tel√©fono v√°lido (7-15 d√≠gitos)');
			return false;
		} else {
			setFieldSuccess(field);
			return true;
		}
	}

	function validateNIT(field) {
		const value = field.value.trim();

		if (value === '') {
			setFieldError(field, 'Este campo es obligatorio');
			return false;
		} else if (value.length < 8 || value.length > 11) {
			setFieldError(field, 'El NIT debe tener entre 8 y 11 d√≠gitos');
			return false;
		} else {
			setFieldSuccess(field);
			return true;
		}
	}

	function setFieldError(field, message) {
		field.classList.remove('is-valid');
		field.classList.add('is-invalid');
		const feedback = field.parentElement.nextElementSibling;
		feedback.textContent = message;
		feedback.className = 'invalid-feedback';
	}

	function setFieldSuccess(field) {
		field.classList.remove('is-invalid');
		field.classList.add('is-valid');
		const feedback = field.parentElement.nextElementSibling;
		feedback.textContent = '';
	}

	function validateForm() {
		const form = document.getElementById('formNuevaEmpresa');
		const inputs = form.querySelectorAll('input[required]');
		let isValid = true;

		inputs.forEach(input => {
			if (input.type === 'email') {
				if (!validateEmail(input)) isValid = false;
			} else if (input.id === 'empresaTelefono') {
				if (!validatePhone(input)) isValid = false;
			} else if (input.id === 'empresaNit') {
				if (!validateNIT(input)) isValid = false;
			} else {
				if (!validateField(input)) isValid = false;
			}
		});

		return isValid;
	}

	// FUNCI√ìN PARA MOSTRAR SWEETALERT2
	function mostrarSweetAlert(data) {
		// Si data es un string que contiene JSON, parsearlo
		let alertData;
		
		if (typeof data === 'string') {
			try {
				// Buscar el JSON dentro del string HTML
				const jsonMatch = data.match(/\{.*\}/s);
				if (jsonMatch) {
					alertData = JSON.parse(jsonMatch[0]);
				} else {
					// Si no es JSON, mostrar mensaje gen√©rico
					alertData = {
						Titulo: 'Informaci√≥n',
						Texto: data,
						Tipo: 'info'
					};
				}
			} catch (e) {
				alertData = {
					Titulo: 'Informaci√≥n',
					Texto: data,
					Tipo: 'info'
				};
			}
		} else {
			alertData = data;
		}

		// Mapear tipos de alerta
		const tipoIcono = {
			'success': 'success',
			'error': 'error',
			'warning': 'warning',
			'info': 'info'
		};

		Swal.fire({
			title: alertData.Titulo || 'Informaci√≥n',
			text: alertData.Texto || 'Operaci√≥n completada',
			icon: tipoIcono[alertData.Tipo] || 'info',
			confirmButtonText: 'Aceptar',
			confirmButtonColor: '#3085d6'
		});
	}

	// FUNCI√ìN PRINCIPAL DE GUARDAR 
	function guardarEmpresa() {
		if (!validateForm()) {
			Swal.fire({
				title: 'Formulario incompleto',
				text: 'Por favor complete todos los campos correctamente',
				icon: 'warning',
				confirmButtonText: 'Entendido',
				confirmButtonColor: '#f39c12'
			});
			return;
		}

		const formData = new FormData(document.getElementById('formNuevaEmpresa'));

		// Crear objeto con los datos usando los nombres que espera el PHP
		const empresaData = new FormData();
		empresaData.append('empresa-nit', formData.get('empresaNit'));
		empresaData.append('empresa-nombre', formData.get('empresaNombre'));
		empresaData.append('empresa-direccion', formData.get('empresaDireccion'));
		empresaData.append('empresa-telefono', formData.get('empresaTelefono'));
		empresaData.append('empresa-email', formData.get('empresaEmail'));
		empresaData.append('empresa-id-representante', formData.get('empresaIdRepresentante'));
		empresaData.append('empresa-nom-representante', formData.get('empresaNomRepresentante'));

		// AGREGAR TOKEN CSRF
		empresaData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

		// Mostrar loading con SweetAlert2
		Swal.fire({
			title: 'Guardando empresa...',
			text: 'Por favor espere mientras procesamos la informaci√≥n',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Enviar datos por AJAX
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: empresaData
		})
		.then(response => response.text())
		.then(data => {
			// Cerrar loading
			Swal.close();

			// Procesar respuesta del PHP
			try {
				// Si la respuesta es JSON directo
				const jsonData = JSON.parse(data);
				mostrarSweetAlert(jsonData);
				
				// Si fue exitoso, cerrar modal y limpiar formulario
				if (jsonData.Tipo === 'success') {
					limpiarModalYCerrar();
					regenerarTokenCSRF('input[name="csrf_token"]', 'formNuevaEmpresa');
					// Opcional: recargar la p√°gina o actualizar la tabla
					setTimeout(() => {
						location.reload();
					}, 1500);
				}
			} catch (e) {
				// Si no es JSON, buscar el sweet_alert en el HTML
				mostrarSweetAlert(data);
				
				// Si contiene palabras de √©xito, cerrar modal
				if (data.includes('success') || data.includes('Empresa registrada') || data.includes('exitoso')) {
					limpiarModalYCerrar();
					regenerarTokenCSRF('input[name="csrf_token"]', 'formNuevaEmpresa');
					setTimeout(() => {
						location.reload();
					}, 1500);
				}
			}
		})
		.catch(error => {
			// Cerrar loading y mostrar error
			Swal.close();
			
			console.error('Error:', error);
			Swal.fire({
				title: 'Error de conexi√≥n',
				text: 'No se pudo conectar con el servidor. Verifique su conexi√≥n a internet.',
				icon: 'error',
				confirmButtonText: 'Reintentar',
				confirmButtonColor: '#e74c3c'
			});
		});
	}

	// FUNCI√ìN PARA LIMPIAR Y CERRAR MODAL (evita duplicaci√≥n)
	function limpiarModalYCerrar() {
		const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaEmpresa'));
		if (modal) modal.hide();

		document.getElementById('formNuevaEmpresa').reset();
		const inputs = document.querySelectorAll('.form-control');
		inputs.forEach(input => {
			input.classList.remove('is-valid', 'is-invalid');
		});

		const feedbacks = document.querySelectorAll('.invalid-feedback');
		feedbacks.forEach(feedback => feedback.textContent = '');
	}

	// FUNCI”N REUTILIZABLE PARA REGENERAR TOKENS CSRF
	function regenerarTokenCSRF(selector, key) {
		// ValidaciÛn b·sica de par·metros
		if (!selector || !key) {
			console.error('Faltan par·metros requeridos: selector y key');
			return;
		}

		const accion = 'csrf_regenerar'; 
		const formData = new FormData();
		formData.append('accion', accion);
		formData.append('key', key);

		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => {
			if (!response.ok) {
				throw new Error(`HTTP ${response.status}: ${response.statusText}`);
			}
			return response.json();
		})
		.then(data => {
			if(data.status === 'success' && data.token) {
				const elemento = document.querySelector(selector) || document.getElementById(selector);
				if(elemento) {
					elemento.value = data.token;
					console.log(`Token ${key} regenerado exitosamente`);
				} else {
					console.error(`No se encontrÛ el elemento con selector: ${selector}`);
				}
			} else {
				console.error('Respuesta inv·lida del servidor:', data);
			}
		})
		.catch(error => {
			console.error(`Error regenerando token ${key}:`, error);
		});
	}

	//===========================================================================================================
	// FUNCI√ìN PRINCIPAL PARA CARGAR EMPRESAS
	//===========================================================================================================
	
	// Variable global para recordar qu√© vista est√° activa
	let vistaActual = 'list';

	function cargarEmpresas(pagina = 1) {
		const shareempresa = document.getElementById('shareempresa').value || '';
		const estadoempresa = document.getElementById('estadoempresa').value || '';
		const csrf_token_list = document.querySelector('input[name="csrf_token_list"]').value;

		// Mostrar loader en la tabla tbody
		const divtable = document.querySelector('.listadoempresas');
		if (divtable) {
			divtable.innerHTML = `<div class="row">
				<div class="col-12 text-center py-5">
					<i class="bi bi-arrow-clockwise spin fa-3x text-muted mb-3"></i>
					<h5 class="text-muted">Cargando...</h5>
					<p class="text-muted">Aqu√≠ se cargan las empresas disponibles</p>
				</div>`;
		}

		const formData = new FormData();
		formData.append('csrf_token_list', csrf_token_list);
		formData.append('shareempresa', shareempresa);
		formData.append('estadoempresa', estadoempresa);
		formData.append('pagina', pagina);
		formData.append('vista_tipo', vistaActual);

		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			console.log('Respuesta del servidor:', data);

			if (data.status === 'success') {
				// Actualizar tabla
				if (data.html_tabla && divtable) {
					divtable.innerHTML = data.html_tabla;
				}

				// Actualizar estad√≠sticas
				if (data.html_estadisticas) {
					const statsRow = document.querySelector('.stats-row');
					if (statsRow) {
						statsRow.innerHTML = data.html_estadisticas;
					}
				}

				// Actualizar contador en header de tabla
				const tableTitleBadge = document.querySelector('.table-title .badge');
				if (tableTitleBadge && data.total_empresas !== undefined) {
					tableTitleBadge.textContent = data.total_empresas;
				}

			} else if (data.Alerta) {
				// Mostrar alerta del sistema
				mostrarSweetAlert(data);

				// Si hay error, mostrar mensaje en tabla
				if (divtable) {
					divtable.innerHTML = `
						<tr>
							<td colspan="8" class="text-center py-4 text-danger">
								<i class="bi bi-exclamation-triangle fa-2x mb-2"></i><br>
								${data.Texto || 'Error al cargar empresas'}
							</td>
						</tr>`;
				}
			}
		})
		.catch(error => {
			console.error('Error:', error);

			if (divtable) {
				divtable.innerHTML = `
					<tr>
						<td colspan="8" class="text-center py-4 text-danger">
							<i class="bi bi-wifi-off fa-2x mb-2"></i><br>
							Error de conexi√≥n. Intenta nuevamente.
						</td>
					</tr>`;
			}

			Swal.fire({
				title: 'Error de conexi√≥n',
				text: 'No se pudo conectar con el servidor',
				icon: 'error',
				confirmButtonText: 'Reintentar',
				confirmButtonColor: '#3085d6'
			}).then(() => {
				cargarEmpresas(pagina);
			});
		});
	}

	//===========================================================================================================
	// FUNCI√ìN PARA FILTRAR EMPRESAS (CON DEBOUNCE)
	//===========================================================================================================
	let filtroTimeout;
	function filtrarEmpresas() {
		clearTimeout(filtroTimeout);
		filtroTimeout = setTimeout(() => {
			cargarEmpresas(1); // Siempre volver a p√°gina 1 al filtrar
		}, 500); // Esperar 500ms despu√©s de que el usuario deje de escribir
	}

	//===========================================================================================================
	// FUNCI√ìN PARA CAMBIAR DE P√ÅGINA
	//===========================================================================================================
	function cargarPagina(pagina) {
		cargarEmpresas(pagina);
	}

	//===========================================================================================================
	// FUNCI√ìN PARA CAMBIAR VISTA
	//===========================================================================================================
	function toggleView(viewType) {
		// Cambiar botones
		const buttons = document.querySelectorAll('.view-btn');
		buttons.forEach(btn => btn.classList.remove('active'));

		if (viewType === 'list') {
			buttons[0].classList.add('active');
			vistaActual = 'list';
		} else {
			buttons[1].classList.add('active');
			vistaActual = 'grid';
		}

		// Recargar con la nueva vista
		cargarEmpresas(1);
	}
	
	function limpiarFiltros() {
		document.getElementById('shareempresa').value = '';
		document.getElementById('estadoempresa').value = '';
		cargarEmpresas(1);
	}
	//===========================================================================================================
	// FUNCIONES PARA ACCIONES DE EMPRESA
	//===========================================================================================================

	function verEmpresa(empresaId) {
		verEmpresaConTabs(empresaId);
	}

	// FUNCI√ìN PARA ELIMINAR EMPRESA
	function eliminarEmpresa(codigoEmpresa, nombreEmpresa, paginaactual) {
		Swal.fire({
			title: '¬øEst√°s seguro?',
			text: `¬øDeseas eliminar la empresa "${nombreEmpresa}"?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'S√≠, eliminar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				eliminarEmpresaAjax(codigoEmpresa, nombreEmpresa,paginaactual);
			}
		});
	}

	// FUNCI√ìN AJAX PARA ELIMINAR EMPRESA
	function eliminarEmpresaAjax(codigoEmpresa, nombreEmpresa, paginaactual) {
		const formData = new FormData();
		formData.append('accion', 'eliminar_empresa');
		formData.append('codigo_empresa', codigoEmpresa);
		formData.append('csrf_token_eliminar', document.querySelector('input[name="csrf_token_list"]').value);

		Swal.fire({
			title: 'Eliminando...',
			text: 'Por favor espere',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})

		.then(response => response.json())
		.then(data => {
			Swal.close();
			mostrarSweetAlert(data);

			if (data.Tipo === 'success') {
				// Recargar listado despu√©s de eliminar
				// Actualizar token si viene en la respuesta
				if (data.nuevo_token) {
					document.querySelector('input[name="csrf_token_list"]').value = data.nuevo_token;
				}
				cargarEmpresas(paginaactual);
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error',
				text: 'No se pudo eliminar la empresa',
				icon: 'error'
			});
		});
	}

	//===========================================================================================================
	// FUNCI”N SIMPLE PARA VER/EDITAR EMPRESA 
	//===========================================================================================================

	function verEmpresaConTabs(empresaId) {
		// Mostrar loading
		Swal.fire({
			title: 'Cargando...',
			text: 'Obteniendo datos de la empresa',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'obtener_empresa');
		formData.append('empresa_id', empresaId);
		formData.append('csrf_token_obtener', document.querySelector('input[name="csrf_token_list"]').value);

		// Enviar petici√≥n
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();

			if (data.status === 'success') {
				// Guardar datos globales
				empresaActualId = empresaId;
				empresaActualData = data.empresa;

				// Llenar el modal con los datos
				llenarModalEmpresaConTabs(data.empresa);
				// Resetear tabs al tab de informaci√≥n
				resetearTabs();

				// Mostrar el modal
				const modal = new bootstrap.Modal(document.getElementById('modalVerEditarEmpresa'));
				modal.show();
			} else {
				mostrarSweetAlert(data);
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error',
				text: 'No se pudo cargar la informaci√≥n',
				icon: 'error'
			});
		});
	}

	// FUNCI√ìN PARA LLENAR EL MODAL CON LOS DATOS
	function llenarModalEmpresa(empresa) {
		llenarModalEmpresaConTabs(empresa);
	}

	// FUNCI√ìN PARA GUARDAR CAMBIOS
	function guardarCambiosEmpresa() {
		// Validar formulario
		if (!validarFormularioEdicion()) {
			Swal.fire({
				title: 'Formulario incompleto',
				text: 'Por favor complete todos los campos correctamente',
				icon: 'warning'
			});
			return;
		}
		
		// Verificar que tenemos el token espec√≠fico
		const tokenEspecifico = document.getElementById('tokenEmpresaEspecifico').value;
		if (!tokenEspecifico) {
			Swal.fire({
				title: 'Error de seguridad',
				text: 'Token de empresa no encontrado. Vuelve a cargar la empresa.',
				icon: 'error'
			});
			return;
		}

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'actualizar_empresa');
		formData.append('empresa_id', document.getElementById('empresaIdEditar').value);
		formData.append('empresa_nit', document.getElementById('editEmpresaNit').value);
		formData.append('empresa_nombre', document.getElementById('editEmpresaNombre').value);
		formData.append('empresa_telefono', document.getElementById('editEmpresaTelefono').value);
		formData.append('empresa_email', document.getElementById('editEmpresaEmail').value);
		formData.append('empresa_direccion', document.getElementById('editEmpresaDireccion').value);
		formData.append('empresa_id_representante', document.getElementById('editEmpresaIdRepresentante').value);
		formData.append('empresa_nom_representante', document.getElementById('editEmpresaNomRepresentante').value);
		formData.append('csrf_token_editar', document.querySelector('input[name="csrf_token_editar"]').value);
		formData.append('token_empresa_especifico', document.querySelector('input[name="tokenEmpresaEspecifico"]').value);
		
		// Mostrar loading
		Swal.fire({
			title: 'Guardando...',
			text: 'Actualizando informaci√≥n de la empresa',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Enviar datos
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();
			mostrarSweetAlert(data);

			if (data.Tipo === 'success') {
				// Cerrar modal
				const modal = bootstrap.Modal.getInstance(document.getElementById('modalVerEditarEmpresa'));
				modal.hide();
				
				// Limpiar token espec√≠fico de la sesi√≥n
            	document.getElementById('tokenEmpresaEspecifico').value = '';
				
				// Regenerar token
				regenerarTokenCSRF('input[name="csrf_token_editar"]', 'editarEmpresa');

				// Recargar tabla
				cargarEmpresas();
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error',
				text: 'No se pudieron guardar los cambios',
				icon: 'error'
			});
		});
	}

	// FUNCI√ìN PARA VALIDAR FORMULARIO
	function validarFormularioEdicion() {
		const campos = [
			{ id: 'editEmpresaNit', tipo: 'numero' },
			{ id: 'editEmpresaNombre', tipo: 'texto' },
			{ id: 'editEmpresaTelefono', tipo: 'telefono' },
			{ id: 'editEmpresaEmail', tipo: 'email' },
			{ id: 'editEmpresaDireccion', tipo: 'texto' },
			{ id: 'editEmpresaIdRepresentante', tipo: 'numero' },
			{ id: 'editEmpresaNomRepresentante', tipo: 'texto' }
		];

		let esValido = true;

		campos.forEach(campo => {
			const elemento = document.getElementById(campo.id);
			const valor = elemento.value.trim();

			// Limpiar estilos previos
			elemento.classList.remove('is-invalid', 'is-valid');

			if (valor === '') {
				elemento.classList.add('is-invalid');
				esValido = false;
			} else if (campo.tipo === 'email') {
				const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if (!emailRegex.test(valor)) {
					elemento.classList.add('is-invalid');
					esValido = false;
				} else {
					elemento.classList.add('is-valid');
				}
			} else if (campo.tipo === 'telefono') {
				const telefonoRegex = /^[0-9]{7,15}$/;
				if (!telefonoRegex.test(valor)) {
					elemento.classList.add('is-invalid');
					esValido = false;
				} else {
					elemento.classList.add('is-valid');
				}
			} else {
				elemento.classList.add('is-valid');
			}
		});

		return esValido;
	}

	// LIMPIAR MODAL AL CERRARLO
	const modalEditarElement = document.getElementById('modalVerEditarEmpresa');
	if (modalEditarElement) {
		modalEditarElement.addEventListener('hidden.bs.modal', function() {
			// Limpiar validaciones
			const campos = document.querySelectorAll('#formVerEditarEmpresa .form-control');
			campos.forEach(campo => {
				campo.classList.remove('is-valid', 'is-invalid');
			});

			// Resetear variables globales
			empresaActualId = null;
			empresaActualData = null;

			// Limpiar contenido de sucursales
			const listaSucursales = document.getElementById('listaSucursales');
			if (listaSucursales) {
				listaSucursales.innerHTML = `
					<div class="text-center py-5">
						<i class="bi bi-geo-alt fa-3x text-muted mb-3"></i>
						<h6 class="text-muted">Selecciona una empresa para ver sus sucursales</h6>
					</div>
				`;
			}

			// Resetear contadores en tabs
			const contadorSucursales = document.getElementById('contadorSucursales');
			const contadorSedes = document.getElementById('contadorSedes');
			if (contadorSucursales) contadorSucursales.textContent = '0';
			if (contadorSedes) contadorSedes.textContent = '0';
		});
	}
	// ACTUALIZAR LAS FUNCIONES EXISTENTES PARA QUE USEN LA MISMA FUNCI√ìN
	function editarEmpresa(empresaId) {
		// Simplemente redirigir a la funci√≥n principal
		verEmpresa(empresaId);
	}
	
	//===========================================================================================================
	// GESTI√ìN DE ESTADOS CON DROPDOWN 
	//===========================================================================================================

	// Event listener para cambios de estado (usar delegaci√≥n de eventos)
	document.addEventListener('click', function(e) {
		if (e.target.classList.contains('cambiar-estado')) {
			e.preventDefault();

			const empresaId = e.target.getAttribute('data-empresa-id');
			const empresaNombre = e.target.getAttribute('data-empresa-nombre');
			const nuevoEstado = e.target.getAttribute('data-nuevo-estado');
			const estadoActual = e.target.closest('.dropdown').querySelector('.estado-dropdown').getAttribute('data-estado-actual');

			mostrarModalCambioEstado(empresaId, empresaNombre, estadoActual, nuevoEstado);
		}
	});

	// FUNCI√ìN PARA MOSTRAR MODAL DE CAMBIO DE ESTADO
	function mostrarModalCambioEstado(empresaId, empresaNombre, estadoActual, nuevoEstado) {

		// Configuraci√≥n de estados
		const configuracionEstados = {
			'Activo': { 
				color: 'success', 
				icono: 'bi-check-circle-fill', 
				descripcion: 'La empresa estar√° completamente operativa y podr√° realizar todas sus actividades normalmente.',
				colorBtn: '#28a745'
			},
			'Inactivo': { 
				color: 'warning', 
				icono: 'bi-pause-circle-fill', 
				descripcion: 'La empresa ser√° temporalmente desactivada. Sus operaciones quedar√°n suspendidas.',
				colorBtn: '#ffc107'
			},
			'Suspendido': { 
				color: 'danger', 
				icono: 'bi-x-circle-fill', 
				descripcion: 'La empresa ser√° suspendida por completo. No podr√° realizar ninguna actividad.',
				colorBtn: '#dc3545'
			}
		};

		const config = configuracionEstados[nuevoEstado];

		// Llenar datos en el modal
		document.getElementById('empresaIdCambioEstado').value = empresaId;
		document.getElementById('nuevoEstadoCambio').value = nuevoEstado;
		document.getElementById('nombreEmpresaCambio').textContent = empresaNombre;
		document.getElementById('estadoActualCambio').textContent = estadoActual;
		document.getElementById('motivoCambioEstado').value = '';

		// Configurar informaci√≥n del nuevo estado
		const infoCard = document.getElementById('infoCambioEstado');
		const icono = document.getElementById('iconoNuevoEstado');
		const titulo = document.getElementById('tituloNuevoEstado');
		const descripcion = document.getElementById('descripcionNuevoEstado');
		const btnConfirmar = document.getElementById('btnConfirmarCambio');

		// Aplicar configuraci√≥n visual
		infoCard.className = `estado-info-card ${config.color}`;
		icono.className = `bi ${config.icono} icono-estado-grande text-${config.color}`;
		titulo.textContent = `Cambiar a ${nuevoEstado}`;
		descripcion.textContent = config.descripcion;
		btnConfirmar.className = `btn btn-${config.color}`;
		btnConfirmar.style.backgroundColor = config.colorBtn;
		btnConfirmar.innerHTML = `<i class="bi bi-check-circle me-1"></i>Cambiar a ${nuevoEstado}`;

		// Limpiar validaciones previas
		const motivo = document.getElementById('motivoCambioEstado');
		motivo.classList.remove('is-valid', 'is-invalid');

		// Mostrar modal
		const modal = new bootstrap.Modal(document.getElementById('modalCambioEstado'));
		modal.show();

		// Enfocar en el textarea
		setTimeout(() => {
			motivo.focus();
		}, 500);
	}
	
	// FUNCI”N PARA CONFIRMAR CAMBIO DE ESTADO (EMPRESAS, SUCURSALES Y SEDES)
	function confirmarCambioEstado() {
		const motivo = document.getElementById('motivoCambioEstado');
		const motivoTexto = motivo.value.trim();

		// Validar motivo
		if (!motivoTexto) {
			motivo.classList.add('is-invalid');
			motivo.focus();
			return;
		}

		if (motivoTexto.length < 10) {
			motivo.classList.add('is-invalid');
			motivo.focus();
			return;
		}

		motivo.classList.remove('is-invalid');
		motivo.classList.add('is-valid');

		// DETECTAR SI ES EMPRESA, SUCURSAL O SEDE
		const btnConfirmar = document.getElementById('btnConfirmarCambio');
		const tipoEntidad = btnConfirmar.getAttribute('data-tipo-entidad') || 'empresa'; // Default: empresa

		const entidadId = document.getElementById('empresaIdCambioEstado').value;
		const nuevoEstado = document.getElementById('nuevoEstadoCambio').value;

		// Ejecutar cambio seg˙n el tipo
		if (tipoEntidad === 'sede') {
			ejecutarCambioEstadoSede(entidadId, nuevoEstado, motivoTexto);
		} else if (tipoEntidad === 'sucursal') {
			ejecutarCambioEstadoSucursal(entidadId, nuevoEstado, motivoTexto);
		} else {
			ejecutarCambioEstado(entidadId, nuevoEstado, motivoTexto);
		}
	}

	// FUNCI√ìN PARA EJECUTAR EL CAMBIO DE ESTADO
	function ejecutarCambioEstado(empresaId, nuevoEstado, motivo) {
		// Cerrar modal primero
		const modal = bootstrap.Modal.getInstance(document.getElementById('modalCambioEstado'));
		modal.hide();

		// Mostrar loading
		Swal.fire({
			title: `Cambiando a ${nuevoEstado}...`,
			text: 'Por favor espere mientras se actualiza el estado de la empresa',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'cambiar_estado_empresa');
		formData.append('empresa_id', empresaId);
		formData.append('nuevo_estado', nuevoEstado);
		formData.append('motivo_cambio', motivo);
		formData.append('csrf_token_estado', document.querySelector('input[name="csrf_token_estado"]').value);

		// Enviar petici√≥n
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();

			// Mostrar resultado
			mostrarSweetAlert(data);

			if (data.Tipo === 'success') {
				// Regenerar token
				regenerarTokenCSRF('input[name="csrf_token_estado"]', 'cambioEstado');
				// Recargar tabla
				cargarEmpresas();
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error de conexi√≥n',
				text: 'No se pudo cambiar el estado de la empresa',
				icon: 'error'
			});
		});
	}

	// VALIDACI√ìN EN TIEMPO REAL DEL MOTIVO
	document.addEventListener('DOMContentLoaded', function() {
		const motivoInput = document.getElementById('motivoCambioEstado');
		if (motivoInput) {
			motivoInput.addEventListener('input', function() {
				const valor = this.value.trim();

				if (valor.length >= 10) {
					this.classList.remove('is-invalid');
					this.classList.add('is-valid');
				} else if (valor.length > 0) {
					this.classList.remove('is-valid');
					this.classList.add('is-invalid');
				} else {
					this.classList.remove('is-valid', 'is-invalid');
				}
			});
		}
	});


	//===========================================================================================================
	// EXPORTACI√ìN A EXCEL
	//===========================================================================================================

	function exportarEmpresas() {
    // Obtener filtros actuales
    const shareempresa = document.getElementById('shareempresa').value || '';
    const estadoempresa = document.getElementById('estadoempresa').value || '';
    
    // Mostrar loading
    Swal.fire({
        title: 'Generando Excel...',
        text: 'Exportando datos de empresas',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Preparar datos
    const formData = new FormData();
    formData.append('accion', 'exportar_empresas_excel');
    formData.append('shareempresa', shareempresa);
    formData.append('estadoempresa', estadoempresa);
    formData.append('csrf_token_export', document.querySelector('input[name="csrf_token_list"]').value);

    // Realizar petici√≥n
    fetch('../ajax/App_empresasAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error del servidor: ' + response.status);
        }

        // Verificar si es JSON (error) o archivo
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(data => {
                throw new Error(data.Texto || 'Error al exportar');
            });
        }

        // Es un archivo Excel - obtener nombre
        const contentDisposition = response.headers.get('content-disposition');
        let nombreArchivo = `Empresas_${new Date().toLocaleDateString('es-CO').replace(/\//g, '-')}_${new Date().toLocaleTimeString('es-CO', {hour12: false}).replace(/:/g, '-')}.xls`;
        
        if (contentDisposition) {
            const match = contentDisposition.match(/filename="(.+)"/);
            if (match && match[1]) {
                nombreArchivo = match[1];
            }
        }

        return response.blob().then(blob => ({ blob, nombreArchivo }));
    })
    .then(({ blob, nombreArchivo }) => {
        Swal.close();

        // Crear y ejecutar descarga
        const url = window.URL.createObjectURL(blob);
        const enlace = document.createElement('a');
        enlace.style.display = 'none';
        enlace.href = url;
        enlace.download = nombreArchivo;
        
        document.body.appendChild(enlace);
        enlace.click();
        
        // Limpiar despu√©s de un momento
        setTimeout(() => {
            document.body.removeChild(enlace);
            window.URL.revokeObjectURL(url);
        }, 100);

        // Mostrar √©xito
        Swal.fire({
            title: '‚úÖ Excel generado',
            text: `Archivo descargado: ${nombreArchivo}`,
            icon: 'success',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        
        Swal.fire({
            title: 'Error al exportar',
            text: error.message || 'No se pudo generar el archivo Excel',
            icon: 'error',
            confirmButtonText: 'Entendido'
        });
    });
}

	
	//===========================================================================================================
	// GESTI√ìN DE TABS EN EL MODAL DE EMPRESA
	// Funciones para manejar la navegaci√≥n por tabs y cargar contenido din√°mico
	//===========================================================================================================

	// Variables globales para los tabs
	let empresaActualId = null;
	let empresaActualData = null;

	// Event listeners para los tabs
	document.addEventListener('DOMContentLoaded', function() {
		// Manejar cambio de tabs
		const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
		tabButtons.forEach(button => {
			button.addEventListener('shown.bs.tab', function(event) {
				const tabId = event.target.getAttribute('data-bs-target');
				manejarCambioTab(tabId);
			});
		});
	});

	// FUNCI√ìN para manejar el cambio de tabs
	function manejarCambioTab(tabId) {
		const modalFooter = document.getElementById('modalFooterInformacion');

		switch(tabId) {
			case '#tabpane-informacion':
				// Mostrar footer con botones de guardar
				if (modalFooter) modalFooter.style.display = 'flex';
				break;

			case '#tabpane-sucursales':
				// Ocultar footer y cargar sucursales
				if (modalFooter) modalFooter.style.display = 'none';
				if (empresaActualId) {
					cargarSucursalesEmpresa(empresaActualId);
				}
				break;

			case '#tabpane-sedes':
				// Ocultar footer y cargar sedes organizadas por sucursal
				if (modalFooter) modalFooter.style.display = 'none';
				if (empresaActualId) {
					recargarSedesSucursal();
				}
				break;
		}
	}
	

	// FUNCI√ìN para llenar el modal con datos (actualizada para tabs)
	function llenarModalEmpresaConTabs(empresa) {
		// Campos de solo lectura
		document.getElementById('verEmpresaCodigo').value = empresa.EmpresaCodigo || '';
		document.getElementById('verEmpresaEstado').value = empresa.EmpresaEstado || '';
		document.getElementById('verEmpresaFechaRegistro').value = empresa.EmpresaFechaRegistro || '';

		// Campos editables
		document.getElementById('editEmpresaNit').value = empresa.EmpresaNit || '';
		document.getElementById('editEmpresaNombre').value = empresa.EmpresaNombre || '';
		document.getElementById('editEmpresaTelefono').value = empresa.EmpresaTelefono || '';
		document.getElementById('editEmpresaEmail').value = empresa.EmpresaEmail || '';
		document.getElementById('editEmpresaDireccion').value = empresa.EmpresaDireccion || '';
		document.getElementById('editEmpresaIdRepresentante').value = empresa.EmpresaIdRepresentante || '';
		document.getElementById('editEmpresaNomRepresentante').value = empresa.EmpresaNomRepresentante || '';
		document.getElementById('empresaIdEditar').value = empresa.EmpresaId;
		document.getElementById('tokenEmpresaEspecifico').value = empresa.TokenEmpresaEspecifico;

		// NUEVO: Actualizar t√≠tulo del modal y nombres en tabs
		document.getElementById('tituloEmpresaModal').textContent = `Gesti√≥n de Empresa: ${empresa.EmpresaNombre}`;
		document.getElementById('nombreEmpresaSucursales').textContent = empresa.EmpresaNombre;

		// Limpiar validaciones previas
		const campos = document.querySelectorAll('#formVerEditarEmpresa .form-control');
		campos.forEach(campo => {
			campo.classList.remove('is-valid', 'is-invalid');
		}); 
		cargarContadorSucursales(empresaActualId);
		console.log('Empresa cargada con tabs:', {
			codigo: empresa.EmpresaCodigo,
			nombre: empresa.EmpresaNombre,
			tieneToken: empresa.TokenEmpresaEspecifico ? 'SI' : 'NO'
		});
	}
	
	// Para cargar solo el contador sin cargar toda la lista
	function cargarContadorSucursales(empresaId) {
		const formData = new FormData();
		formData.append('accion', 'contar_sucursales_empresa');
		formData.append('empresa_id', empresaId);
		formData.append('csrf_token_contador', document.querySelector('input[name="csrf_token_list"]').value);

		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
				// Actualizar contador en el tab
				const contador = document.getElementById('contadorSucursales');
				if (contador) {
					contador.textContent = data.total_sucursales || 0;
				}
			}
		})
		.catch(error => {
			console.error('Error cargando contador de sucursales:', error);
		});
	}

	// FUNCI√ìN para resetear tabs al tab principal
	function resetearTabs() {
		// Activar el primer tab (Informaci√≥n)
		const firstTab = document.getElementById('tab-informacion');
		const firstTabPane = document.getElementById('tabpane-informacion');

		// Remover active de todos los tabs
		document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
		document.querySelectorAll('.tab-pane').forEach(pane => {
			pane.classList.remove('show', 'active');
		});

		// Activar el primer tab
		firstTab.classList.add('active');
		firstTabPane.classList.add('show', 'active');

		// Mostrar footer
		const modalFooter = document.getElementById('modalFooterInformacion');
		if (modalFooter) modalFooter.style.display = 'flex';
	}

	//===========================================================================================================
	// GESTI√ìN DE SUCURSALES EN EL TAB
	// Funciones espec√≠ficas para manejar las sucursales dentro del modal
	//===========================================================================================================

	// FUNCI√ìN para cargar sucursales de una empresa
	function cargarSucursalesEmpresa(empresaId) {
		const listaSucursales = document.getElementById('listaSucursales');

		if (!listaSucursales) return;

		// Mostrar loading
		listaSucursales.innerHTML = `
			<div class="text-center py-5">
				<i class="bi bi-arrow-clockwise spin fa-2x text-muted mb-3"></i>
				<h6 class="text-muted">Cargando sucursales...</h6>
			</div>
		`;

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'listar_sucursales_empresa');
		formData.append('empresa_id', empresaId);
		formData.append('filtro_nombre', document.getElementById('filtroSucursales')?.value || '');
		formData.append('filtro_estado', document.getElementById('filtroEstadoSucursales')?.value || '');
		formData.append('csrf_token_sucursales', document.querySelector('input[name="csrf_token_list"]').value);

		// Enviar petici√≥n
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
				listaSucursales.innerHTML = data.html_sucursales;

				// Actualizar contador en el tab
				const contador = document.getElementById('contadorSucursales');
				if (contador) {
					contador.textContent = data.total_sucursales || 0;
				}
			} else {
				listaSucursales.innerHTML = `
					<div class="text-center py-5 text-danger">
						<i class="bi bi-exclamation-triangle fa-2x mb-3"></i>
						<h6>Error al cargar sucursales</h6>
						<p class="mb-0">${data.Texto || 'Error desconocido'}</p>
					</div>
				`;
			}
		})
		.catch(error => {
			console.error('Error cargando sucursales:', error);
			listaSucursales.innerHTML = `
				<div class="text-center py-5 text-danger">
					<i class="bi bi-wifi-off fa-2x mb-3"></i>
					<h6>Error de conexi√≥n</h6>
					<p class="mb-0">No se pudo conectar con el servidor</p>
				</div>
			`;
		});
	}

	// FUNCI√ìN para mostrar modal de nueva sucursal
	function mostrarModalNuevaSucursal() {
		if (!empresaActualData) {
			Swal.fire({
				title: 'Error',
				text: 'No se ha seleccionado una empresa v√°lida',
				icon: 'error'
			});
			return;
		}

		// Llenar datos de la empresa padre
		document.getElementById('nombreEmpresaPadre').textContent = empresaActualData.EmpresaNombre;
		document.getElementById('empresa_id_sucursal').value = empresaActualData.EmpresaId;

		// Limpiar formulario
		limpiarFormularioSucursal();

		// Mostrar modal
		const modal = new bootstrap.Modal(document.getElementById('modalNuevaSucursal'));
		modal.show();
	}

	// FUNCI√ìN para limpiar filtros de sucursales
	function limpiarFiltrosSucursales() {
		document.getElementById('filtroSucursales').value = '';
		document.getElementById('filtroEstadoSucursales').value = '';

		if (empresaActualId) {
			cargarSucursalesEmpresa(empresaActualId);
		}
	}

	// EVENT LISTENERS para filtros de sucursales
	document.addEventListener('DOMContentLoaded', function() {
		// Filtro de b√∫squeda con debounce
		let filtroTimeout;
		const filtroSucursales = document.getElementById('filtroSucursales');
		if (filtroSucursales) {
			filtroSucursales.addEventListener('input', function() {
				clearTimeout(filtroTimeout);
				filtroTimeout = setTimeout(() => {
					if (empresaActualId) {
						cargarSucursalesEmpresa(empresaActualId);
					}
				}, 500);
			});
		}

		// Filtro de estado
		const filtroEstadoSucursales = document.getElementById('filtroEstadoSucursales');
		if (filtroEstadoSucursales) {
			filtroEstadoSucursales.addEventListener('change', function() {
				if (empresaActualId) {
					cargarSucursalesEmpresa(empresaActualId);
				}
			});
		}
	});

	//===========================================================================================================
	// GESTI√ìN DE MODAL NUEVA SUCURSAL
	// Funciones para crear nuevas sucursales
	//===========================================================================================================

	// FUNCI√ìN para limpiar formulario de sucursal
	function limpiarFormularioSucursal() {
		const form = document.getElementById('formNuevaSucursal');
		if (form) {
			form.reset();

			// Limpiar validaciones
			const campos = form.querySelectorAll('.form-control');
			campos.forEach(campo => {
				campo.classList.remove('is-valid', 'is-invalid');
			});

			const feedbacks = form.querySelectorAll('.invalid-feedback');
			feedbacks.forEach(feedback => feedback.textContent = '');
		}
	}

	// FUNCI√ìN para autocompletar datos desde la empresa
	function autocompletarDesdeEmpresa() {
		if (!empresaActualData) return;

		// Confirmar acci√≥n
		Swal.fire({
			title: '¬øAutocompletar datos?',
			text: 'Esto copiar√° los datos de contacto de la empresa principal',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'S√≠, autocompletar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				// Autocompletar campos
				document.getElementById('sucursalTelefono').value = empresaActualData.EmpresaTelefono;
				document.getElementById('sucursalEmail').value = empresaActualData.EmpresaEmail;
				document.getElementById('sucursalDireccion').value = empresaActualData.EmpresaDireccion;
				document.getElementById('sucursalIdRepresentante').value = empresaActualData.EmpresaIdRepresentante;
				document.getElementById('sucursalNomRepresentante').value = empresaActualData.EmpresaNomRepresentante;

				// Mostrar mensaje
				Swal.fire({
					title: 'Datos copiados',
					text: 'Los datos de contacto han sido autocompletados',
					icon: 'success',
					timer: 2000,
					showConfirmButton: false
				});
			}
		});
	}

	//===========================================================================================================
	// VALIDACIONES PARA SUCURSAL
	//===========================================================================================================

	// Event listeners para validaciones en tiempo real
	document.addEventListener('DOMContentLoaded', function() {
		const formSucursal = document.getElementById('formNuevaSucursal');
		if (formSucursal) {
			const inputs = formSucursal.querySelectorAll('input[required]');

			inputs.forEach(input => {
				input.addEventListener('blur', function() {
					validateFieldSucursal(this);
				});

				input.addEventListener('input', function() {
					if (this.classList.contains('is-invalid')) {
						validateFieldSucursal(this);
					}
				});
			});

			// Validaciones espec√≠ficas
			document.getElementById('sucursalEmail').addEventListener('input', function() {
				validateEmailSucursal(this);
			});

			document.getElementById('sucursalTelefono').addEventListener('input', function() {
				validatePhoneSucursal(this);
			});

			document.getElementById('sucursalNit').addEventListener('input', function() {
				validateNITSucursal(this);
			});
		}
	});

	// FUNCIONES DE VALIDACI√ìN para sucursal
	function validateFieldSucursal(field) {
		const value = field.value.trim();

		if (value === '') {
			setFieldErrorSucursal(field, 'Este campo es obligatorio');
			return false;
		} else {
			setFieldSuccessSucursal(field);
			return true;
		}
	}

	function validateEmailSucursal(field) {
		const value = field.value.trim();
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

		if (value === '') {
			setFieldErrorSucursal(field, 'Este campo es obligatorio');
			return false;
		} else if (!emailRegex.test(value)) {
			setFieldErrorSucursal(field, 'Ingrese un email v√°lido');
			return false;
		} else {
			setFieldSuccessSucursal(field);
			return true;
		}
	}

	function validatePhoneSucursal(field) {
		const value = field.value.trim();
		const phoneRegex = /^[0-9]{7,15}$/;

		if (value === '') {
			setFieldErrorSucursal(field, 'Este campo es obligatorio');
			return false;
		} else if (!phoneRegex.test(value)) {
			setFieldErrorSucursal(field, 'Ingrese un tel√©fono v√°lido (7-15 d√≠gitos)');
			return false;
		} else {
			setFieldSuccessSucursal(field);
			return true;
		}
	}

	function validateNITSucursal(field) {
		const value = field.value.trim();

		if (value === '') {
			setFieldErrorSucursal(field, 'Este campo es obligatorio');
			return false;
		} else if (value.length < 8 || value.length > 15) {
			setFieldErrorSucursal(field, 'El NIT debe tener entre 8 y 15 d√≠gitos');
			return false;
		} else {
			setFieldSuccessSucursal(field);
			return true;
		}
	}

	function setFieldErrorSucursal(field, message) {
		field.classList.remove('is-valid');
		field.classList.add('is-invalid');
		const feedback = field.parentElement.nextElementSibling;
		if (feedback && feedback.classList.contains('invalid-feedback')) {
			feedback.textContent = message;
		}
	}

	function setFieldSuccessSucursal(field) {
		field.classList.remove('is-invalid');
		field.classList.add('is-valid');
		const feedback = field.parentElement.nextElementSibling;
		if (feedback && feedback.classList.contains('invalid-feedback')) {
			feedback.textContent = '';
		}
	}

	function validateFormSucursal() {
		const form = document.getElementById('formNuevaSucursal');
		const inputs = form.querySelectorAll('input[required]');
		let isValid = true;

		inputs.forEach(input => {
			if (input.type === 'email') {
				if (!validateEmailSucursal(input)) isValid = false;
			} else if (input.id === 'sucursalTelefono') {
				if (!validatePhoneSucursal(input)) isValid = false;
			} else if (input.id === 'sucursalNit') {
				if (!validateNITSucursal(input)) isValid = false;
			} else {
				if (!validateFieldSucursal(input)) isValid = false;
			}
		});

		return isValid;
	}

	//===========================================================================================================
	// GUARDAR NUEVA SUCURSAL
	//===========================================================================================================

	function guardarNuevaSucursal() {
		if (!validateFormSucursal()) {
			Swal.fire({
				title: 'Formulario incompleto',
				text: 'Por favor complete todos los campos correctamente',
				icon: 'warning',
				confirmButtonText: 'Entendido',
				confirmButtonColor: '#f39c12'
			});
			return;
		}

		const formData = new FormData(document.getElementById('formNuevaSucursal'));

		// Crear objeto con los datos usando los nombres que espera el PHP
		const sucursalData = new FormData();
		sucursalData.append('sucursal-nit', formData.get('sucursalNit'));
		sucursalData.append('sucursal-nombre', formData.get('sucursalNombre'));
		sucursalData.append('sucursal-direccion', formData.get('sucursalDireccion'));
		sucursalData.append('sucursal-telefono', formData.get('sucursalTelefono'));
		sucursalData.append('sucursal-email', formData.get('sucursalEmail'));
		sucursalData.append('sucursal-id-representante', formData.get('sucursalIdRepresentante'));
		sucursalData.append('sucursal-nom-representante', formData.get('sucursalNomRepresentante'));
		sucursalData.append('empresa-id', formData.get('empresa_id_sucursal'));

		// AGREGAR TOKEN CSRF
		sucursalData.append('csrf_token', formData.get('csrf_token_sucursal'));

		// Mostrar loading con SweetAlert2
		Swal.fire({
			title: 'Guardando sucursal...',
			text: 'Por favor espere mientras procesamos la informaci√≥n',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Enviar datos por AJAX
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: sucursalData
		})
		.then(response => response.text())
		.then(data => {
			// Cerrar loading
			Swal.close();

			// Procesar respuesta del PHP
			try {
				// Si la respuesta es JSON directo
				const jsonData = JSON.parse(data);
				mostrarSweetAlert(jsonData);

				// Si fue exitoso, cerrar modal y recargar sucursales
				if (jsonData.Tipo === 'success') {
					limpiarModalSucursalYCerrar();
					regenerarTokenCSRF('input[name="csrf_token_sucursal"]', 'formNuevaSucursal');
					// Recargar contador y lista de sucursales
					if (empresaActualId) {
						cargarContadorSucursales(empresaActualId);
						cargarSucursalesEmpresa(empresaActualId);
					}
				}
			} catch (e) {
				// Si no es JSON, buscar el sweet_alert en el HTML
				mostrarSweetAlert(data);

				// Si contiene palabras de √©xito, cerrar modal
				if (data.includes('success') || data.includes('Sucursal registrada') || data.includes('exitoso')) {
					limpiarModalSucursalYCerrar();
					regenerarTokenCSRF('input[name="csrf_token_sucursal"]', 'formNuevaSucursal');

					// Recargar contador y lista de sucursales
					if (empresaActualId) {
						cargarContadorSucursales(empresaActualId);
						cargarSucursalesEmpresa(empresaActualId);
					}
				}
			}
		})
		.catch(error => {
			// Cerrar loading y mostrar error
			Swal.close();

			console.error('Error:', error);
			Swal.fire({
				title: 'Error de conexi√≥n',
				text: 'No se pudo conectar con el servidor. Verifique su conexi√≥n a internet.',
				icon: 'error',
				confirmButtonText: 'Reintentar',
				confirmButtonColor: '#e74c3c'
			});
		});
	}

	// FUNCI√ìN para limpiar y cerrar modal de sucursal
	function limpiarModalSucursalYCerrar() {
		const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaSucursal'));
		if (modal) modal.hide();

		limpiarFormularioSucursal();
	}


	//===========================================================================================================
	// GESTI√ìN DE VER/EDITAR SUCURSAL
	// Funciones para ver y editar sucursales existentes
	//===========================================================================================================

	// FUNCI√ìN para ver/editar sucursal
	function verSucursal(sucursalId) {
		// Mostrar loading
		Swal.fire({
			title: 'Cargando...',
			text: 'Obteniendo datos de la sucursal',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'obtener_sucursal');
		formData.append('sucursal_id', sucursalId);
		formData.append('csrf_token_obtener_sucursal', document.querySelector('input[name="csrf_token_list"]').value);

		// Enviar petici√≥n
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();

			if (data.status === 'success') {
				// Llenar el modal con los datos
				llenarModalSucursal(data.sucursal);

				// Mostrar el modal
				const modal = new bootstrap.Modal(document.getElementById('modalVerEditarSucursal'));
				modal.show();
			} else {
				mostrarSweetAlert(data);
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error',
				text: 'No se pudo cargar la informaci√≥n de la sucursal',
				icon: 'error'
			});
		});
	}

	// FUNCI√ìN para llenar el modal con datos de la sucursal
	function llenarModalSucursal(sucursal) {
		// Campos de solo lectura
		document.getElementById('verSucursalCodigo').value = sucursal.SucursalCodigo || '';
		document.getElementById('verSucursalEstado').value = sucursal.SucursalEstado || '';
		document.getElementById('verSucursalFechaRegistro').value = sucursal.SucursalFechaRegistro || '';
		document.getElementById('verSucursalEmpresa').value = sucursal.EmpresaNombre || '';

		// Campos editables
		document.getElementById('editSucursalNit').value = sucursal.SucursalNit || '';
		document.getElementById('editSucursalNombre').value = sucursal.SucursalNombre || '';
		document.getElementById('editSucursalTelefono').value = sucursal.SucursalTelefono || '';
		document.getElementById('editSucursalEmail').value = sucursal.SucursalEmail || '';
		document.getElementById('editSucursalDireccion').value = sucursal.SucursalDireccion || '';
		document.getElementById('editSucursalIdRepresentante').value = sucursal.SucursalIdRepresentante || '';
		document.getElementById('editSucursalNomRepresentante').value = sucursal.SucursalNomRepresentante || '';
		document.getElementById('sucursalIdEditar').value = sucursal.SucursalId;
		document.getElementById('tokenSucursalEspecifico').value = sucursal.TokenSucursalEspecifico;

		// Actualizar t√≠tulo del modal
		document.getElementById('tituloSucursalModal').textContent = `Gesti√≥n: ${sucursal.SucursalNombre}`;

		// Limpiar validaciones previas
		const campos = document.querySelectorAll('#formVerEditarSucursal .form-control');
		campos.forEach(campo => {
			campo.classList.remove('is-valid', 'is-invalid');
		});

		console.log('Sucursal cargada:', {
			codigo: sucursal.SucursalCodigo,
			nombre: sucursal.SucursalNombre,
			empresa: sucursal.EmpresaNombre,
			tieneToken: sucursal.TokenSucursalEspecifico ? 'SI' : 'NO'
		});
	}



	//===========================================================================================================
	// GUARDAR CAMBIOS DE SUCURSAL
	//===========================================================================================================

	function guardarCambiosSucursal() {
		// Validar formulario
		if (!validarFormularioEdicionSucursal()) {
			Swal.fire({
				title: 'Formulario incompleto',
				text: 'Por favor complete todos los campos correctamente',
				icon: 'warning'
			});
			return;
		}

		// Verificar que tenemos el token espec√≠fico
		const tokenEspecifico = document.getElementById('tokenSucursalEspecifico').value;
		if (!tokenEspecifico) {
			Swal.fire({
				title: 'Error de seguridad',
				text: 'Token de sucursal no encontrado. Vuelve a cargar la sucursal.',
				icon: 'error'
			});
			return;
		}

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'actualizar_sucursal');
		formData.append('sucursal_id', document.getElementById('sucursalIdEditar').value);
		formData.append('sucursal_nit', document.getElementById('editSucursalNit').value);
		formData.append('sucursal_nombre', document.getElementById('editSucursalNombre').value);
		formData.append('sucursal_telefono', document.getElementById('editSucursalTelefono').value);
		formData.append('sucursal_email', document.getElementById('editSucursalEmail').value);
		formData.append('sucursal_direccion', document.getElementById('editSucursalDireccion').value);
		formData.append('sucursal_id_representante', document.getElementById('editSucursalIdRepresentante').value);
		formData.append('sucursal_nom_representante', document.getElementById('editSucursalNomRepresentante').value);
		formData.append('csrf_token_editar_sucursal', document.querySelector('input[name="csrf_token_editar_sucursal"]').value);
		formData.append('token_sucursal_especifico', document.querySelector('input[name="tokenSucursalEspecifico"]').value);

		// Mostrar loading
		Swal.fire({
			title: 'Guardando...',
			text: 'Actualizando informaci√≥n de la sucursal',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Enviar datos
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();
			mostrarSweetAlert(data);

			if (data.Tipo === 'success') {
				// Cerrar modal
				const modal = bootstrap.Modal.getInstance(document.getElementById('modalVerEditarSucursal'));
				modal.hide();

				// Limpiar token espec√≠fico
				document.getElementById('tokenSucursalEspecifico').value = '';

				// Regenerar token
				regenerarTokenCSRF('input[name="csrf_token_editar_sucursal"]', 'editarSucursal');

				// Recargar lista de sucursales
				if (empresaActualId) {
					cargarSucursalesEmpresa(empresaActualId);
				}
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error',
				text: 'No se pudieron guardar los cambios',
				icon: 'error'
			});
		});
	}

	//===========================================================================================================
	// VALIDACIONES PARA EDICI√ìN DE SUCURSAL
	//===========================================================================================================

	function validarFormularioEdicionSucursal() {
		const campos = [
			{ id: 'editSucursalNit', tipo: 'numero' },
			{ id: 'editSucursalNombre', tipo: 'texto' },
			{ id: 'editSucursalTelefono', tipo: 'telefono' },
			{ id: 'editSucursalEmail', tipo: 'email' },
			{ id: 'editSucursalDireccion', tipo: 'texto' },
			{ id: 'editSucursalIdRepresentante', tipo: 'numero' },
			{ id: 'editSucursalNomRepresentante', tipo: 'texto' }
		];

		let esValido = true;

		campos.forEach(campo => {
			const elemento = document.getElementById(campo.id);
			const valor = elemento.value.trim();

			// Limpiar estilos previos
			elemento.classList.remove('is-invalid', 'is-valid');

			if (valor === '') {
				elemento.classList.add('is-invalid');
				esValido = false;
			} else if (campo.tipo === 'email') {
				const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if (!emailRegex.test(valor)) {
					elemento.classList.add('is-invalid');
					esValido = false;
				} else {
					elemento.classList.add('is-valid');
				}
			} else if (campo.tipo === 'telefono') {
				const telefonoRegex = /^[0-9]{7,15}$/;
				if (!telefonoRegex.test(valor)) {
					elemento.classList.add('is-invalid');
					esValido = false;
				} else {
					elemento.classList.add('is-valid');
				}
			} else {
				elemento.classList.add('is-valid');
			}
		});

		return esValido;
	}

	//===========================================================================================================
	// GESTI√ìN DE ESTADOS DE SUCURSAL
	//===========================================================================================================


	// FUNCI√ìN PARA EJECUTAR EL CAMBIO DE ESTADO DE SUCURSAL
	function ejecutarCambioEstadoSucursal(sucursalId, nuevoEstado, motivo) {
		// Cerrar modal primero
		const modal = bootstrap.Modal.getInstance(document.getElementById('modalCambioEstado'));
		modal.hide();

		// Mostrar loading
		Swal.fire({
			title: `Cambiando a ${nuevoEstado}...`,
			text: 'Por favor espere mientras se actualiza el estado de la sucursal',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'cambiar_estado_sucursal');
		formData.append('sucursal_id', sucursalId);
		formData.append('nuevo_estado', nuevoEstado);
		formData.append('motivo_cambio', motivo);
		formData.append('csrf_token_estado_sucursal', document.querySelector('input[name="csrf_token_list"]').value);

		// Enviar petici√≥n
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();

			// Mostrar resultado
			mostrarSweetAlert(data);

			if (data.Tipo === 'success') {
				// Regenerar token
				regenerarTokenCSRF('input[name="csrf_token_list"]', 'listEmpresas');

				// Recargar lista de sucursales si estamos en el tab de sucursales
				if (empresaActualId) {
					cargarSucursalesEmpresa(empresaActualId);
				}
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error de conexi√≥n',
				text: 'No se pudo cambiar el estado de la sucursal',
				icon: 'error'
			});
		});
	}

	//===========================================================================================================
	// ELIMINAR SUCURSAL
	//===========================================================================================================

	function eliminarSucursal(sucursalId, sucursalNombre) {
		Swal.fire({
			title: '¬øEst√°s seguro?',
			text: `¬øDeseas eliminar la sucursal "${sucursalNombre}"?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'S√≠, eliminar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				ejecutarEliminacionSucursal(sucursalId, sucursalNombre);
			}
		});
	}

	function ejecutarEliminacionSucursal(sucursalId, sucursalNombre) {
		const formData = new FormData();
		formData.append('accion', 'eliminar_sucursal');
		formData.append('sucursal_id', sucursalId);
		formData.append('csrf_token_eliminar_sucursal', document.querySelector('input[name="csrf_token_list"]').value);

		Swal.fire({
			title: 'Eliminando...',
			text: 'Por favor espere',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();
			mostrarSweetAlert(data);

			if (data.Tipo === 'success') {
				
				// Cerrar modal si est√° abierto
				const modal = bootstrap.Modal.getInstance(document.getElementById('modalVerEditarSucursal'));
				if (modal) modal.hide();

				// Recargar lista de sucursales
				if (empresaActualId) {
					cargarContadorSucursales(empresaActualId);
					cargarSucursalesEmpresa(empresaActualId);
				}
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error',
				text: 'No se pudo eliminar la sucursal',
				icon: 'error'
			});
		});
	}

	//===========================================================================================================
	// FUNCIONES AUXILIARES PARA SUCURSALES
	//===========================================================================================================


	// LIMPIAR MODAL AL CERRARLO
	const modalEditarSucursalElement = document.getElementById('modalVerEditarSucursal');
	if (modalEditarSucursalElement) {
		modalEditarSucursalElement.addEventListener('hidden.bs.modal', function() {
			// Limpiar validaciones
			const campos = document.querySelectorAll('#formVerEditarSucursal .form-control');
			campos.forEach(campo => {
				campo.classList.remove('is-valid', 'is-invalid');
			});

			// Limpiar token espec√≠fico
			document.getElementById('tokenSucursalEspecifico').value = '';
		});
	}

	
	//===========================================================================================================
	// GESTI√ìN DE ESTADOS DE SUCURSALES CON DROPDOWN (IGUAL QUE EMPRESAS)
	//===========================================================================================================

	// Event listener para cambios de estado de SUCURSALES (usar delegaci√≥n de eventos)
	document.addEventListener('click', function(e) {
		if (e.target.classList.contains('cambiar-estado-sucursal')) {

			e.preventDefault();

			const sucursalId = e.target.getAttribute('data-sucursal-id');
			const sucursalNombre = e.target.getAttribute('data-sucursal-nombre');
			const nuevoEstado = e.target.getAttribute('data-nuevo-estado');
			const estadoActual = e.target.closest('.dropdown').querySelector('.estado-dropdown').getAttribute('data-estado-actual');

			mostrarModalCambioEstadoSucursal(sucursalId, sucursalNombre, estadoActual, nuevoEstado);
		}
	});

	// FUNCI”N PARA MOSTRAR MODAL DE CAMBIO DE ESTADO DE SUCURSAL
	function mostrarModalCambioEstadoSucursal(sucursalId, sucursalNombre, estadoActual, nuevoEstado) {

		// Configuraci√≥n de estados (igual que empresas)
		const configuracionEstados = {
			'Activo': { 
				color: 'success', 
				icono: 'bi-check-circle-fill', 
				descripcion: 'La sucursal estar√° completamente operativa y podr√° realizar todas sus actividades normalmente.',
				colorBtn: '#28a745'
			},
			'Inactivo': { 
				color: 'warning', 
				icono: 'bi-pause-circle-fill', 
				descripcion: 'La sucursal ser√° temporalmente desactivada. Sus operaciones quedar√°n suspendidas.',
				colorBtn: '#ffc107'
			},
			'Suspendido': { 
				color: 'danger', 
				icono: 'bi-x-circle-fill', 
				descripcion: 'La sucursal ser√° suspendida por completo. No podr√° realizar ninguna actividad.',
				colorBtn: '#dc3545'
			}
		};

		const config = configuracionEstados[nuevoEstado];

		// Llenar datos en el modal (reutilizar el mismo modal de empresas)
		document.getElementById('empresaIdCambioEstado').value = sucursalId;
		document.getElementById('nuevoEstadoCambio').value = nuevoEstado;
		document.getElementById('nombreEmpresaCambio').textContent = sucursalNombre;
		document.getElementById('estadoActualCambio').textContent = estadoActual;
		document.getElementById('motivoCambioEstado').value = '';

		// Configurar informaci√≥n del nuevo estado
		const infoCard = document.getElementById('infoCambioEstado');
		const icono = document.getElementById('iconoNuevoEstado');
		const titulo = document.getElementById('tituloNuevoEstado');
		const descripcion = document.getElementById('descripcionNuevoEstado');
		const btnConfirmar = document.getElementById('btnConfirmarCambio');

		// Aplicar configuraci√≥n visual
		infoCard.className = `estado-info-card ${config.color}`;
		icono.className = `bi ${config.icono} icono-estado-grande text-${config.color}`;
		titulo.textContent = `Cambiar a ${nuevoEstado}`;
		descripcion.textContent = config.descripcion;
		btnConfirmar.className = `btn btn-${config.color}`;
		btnConfirmar.style.backgroundColor = config.colorBtn;
		btnConfirmar.innerHTML = `<i class="bi bi-check-circle me-1"></i>Cambiar a ${nuevoEstado}`;

		// üÜï MARCAR QUE ES UNA SUCURSAL para el procesamiento
		btnConfirmar.setAttribute('data-tipo-entidad', 'sucursal');

		// Limpiar validaciones previas
		const motivo = document.getElementById('motivoCambioEstado');
		motivo.classList.remove('is-valid', 'is-invalid');

		// Mostrar modal (reutilizar el de empresas)
		const modal = new bootstrap.Modal(document.getElementById('modalCambioEstado'));
		modal.show();

		// Enfocar en el textarea
		setTimeout(() => {
			motivo.focus();
		}, 500);
	}

	//===========================================================================================================
	// FUNCIONES JAVASCRIPT PARA GESTI”N DE SEDES
	// Siguiendo el mismo patrÛn que las sucursales
	//===========================================================================================================

	//===========================================================================================================
	// MOSTRAR MODAL NUEVA SEDE
	// FunciÛn para mostrar el modal con los datos de la sucursal padre
	//===========================================================================================================
	function mostrarModalNuevaSede() {
		// Verificar que tenemos datos de sucursal actual
		if (!sucursalActualData) {
			Swal.fire({
				title: 'Error',
				text: 'No se ha seleccionado una sucursal v·lida',
				icon: 'error'
			});
			return;
		}

		// Llenar datos de la sucursal padre
		document.getElementById('nombreSucursalPadre').textContent = sucursalActualData.SucursalNombre;
		document.getElementById('sucursal_id_sede').value = sucursalActualData.SucursalId;

		// Limpiar formulario
		limpiarFormularioSede();

		// Mostrar modal
		const modal = new bootstrap.Modal(document.getElementById('modalNuevaSede'));
		modal.show();
	}

	//===========================================================================================================
	// LIMPIAR FORMULARIO DE SEDE
	// FunciÛn para resetear el formulario y validaciones
	//===========================================================================================================
	function limpiarFormularioSede() {
		const form = document.getElementById('formNuevaSede');
		if (form) {
			form.reset();

			// Limpiar validaciones
			const campos = form.querySelectorAll('.form-control');
			campos.forEach(campo => {
				campo.classList.remove('is-valid', 'is-invalid');
			});

			const feedbacks = form.querySelectorAll('.invalid-feedback');
			feedbacks.forEach(feedback => feedback.textContent = '');
		}
	}

	//===========================================================================================================
	// AUTOCOMPLETAR DESDE SUCURSAL
	// FunciÛn para copiar datos de contacto de la sucursal
	//===========================================================================================================
	function autocompletarDesdeSucursal() {
		if (!sucursalActualData) return;

		// Confirmar acciÛn
		Swal.fire({
			title: 'øAutocompletar datos?',
			text: 'Esto copiar· los datos de contacto de la sucursal',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'SÌ, autocompletar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				// Autocompletar campos
				document.getElementById('sedeTelefono').value = sucursalActualData.SucursalTelefono;
				document.getElementById('sedeEmail').value = sucursalActualData.SucursalEmail;
				document.getElementById('sedeDireccion').value = sucursalActualData.SucursalDireccion;
				document.getElementById('sedeIdRepresentante').value = sucursalActualData.SucursalIdRepresentante;
				document.getElementById('sedeNomRepresentante').value = sucursalActualData.SucursalNomRepresentante;

				// Mostrar mensaje
				Swal.fire({
					title: 'Datos copiados',
					text: 'Los datos de contacto han sido autocompletados',
					icon: 'success',
					timer: 2000,
					showConfirmButton: false
				});
			}
		});
	}

	//===========================================================================================================
	// VALIDACIONES PARA SEDE
	// Event listeners para validaciones en tiempo real
	//===========================================================================================================
	document.addEventListener('DOMContentLoaded', function() {
		const formSede = document.getElementById('formNuevaSede');
		if (formSede) {
			const inputs = formSede.querySelectorAll('input[required]');

			inputs.forEach(input => {
				input.addEventListener('blur', function() {
					validateFieldSede(this);
				});

				input.addEventListener('input', function() {
					if (this.classList.contains('is-invalid')) {
						validateFieldSede(this);
					}
				});
			});

			// Validaciones especÌficas
			document.getElementById('sedeEmail').addEventListener('input', function() {
				validateEmailSede(this);
			});

			document.getElementById('sedeTelefono').addEventListener('input', function() {
				validatePhoneSede(this);
			});

			document.getElementById('sedeNit').addEventListener('input', function() {
				validateNITSede(this);
			});
		}
	});

	//===========================================================================================================
	// FUNCIONES DE VALIDACI”N PARA SEDE
	//===========================================================================================================
	function validateFieldSede(field) {
		const value = field.value.trim();

		if (value === '') {
			setFieldErrorSede(field, 'Este campo es obligatorio');
			return false;
		} else {
			setFieldSuccessSede(field);
			return true;
		}
	}

	function validateEmailSede(field) {
		const value = field.value.trim();
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

		if (value === '') {
			setFieldErrorSede(field, 'Este campo es obligatorio');
			return false;
		} else if (!emailRegex.test(value)) {
			setFieldErrorSede(field, 'Ingrese un email v·lido');
			return false;
		} else {
			setFieldSuccessSede(field);
			return true;
		}
	}

	function validatePhoneSede(field) {
		const value = field.value.trim();
		const phoneRegex = /^[0-9]{7,15}$/;

		if (value === '') {
			setFieldErrorSede(field, 'Este campo es obligatorio');
			return false;
		} else if (!phoneRegex.test(value)) {
			setFieldErrorSede(field, 'Ingrese un telÈfono v·lido (7-15 dÌgitos)');
			return false;
		} else {
			setFieldSuccessSede(field);
			return true;
		}
	}

	function validateNITSede(field) {
		const value = field.value.trim();

		if (value === '') {
			setFieldErrorSede(field, 'Este campo es obligatorio');
			return false;
		} else if (value.length < 8 || value.length > 15) {
			setFieldErrorSede(field, 'El NIT debe tener entre 8 y 15 dÌgitos');
			return false;
		} else {
			setFieldSuccessSede(field);
			return true;
		}
	}

	function setFieldErrorSede(field, message) {
		field.classList.remove('is-valid');
		field.classList.add('is-invalid');
		const feedback = field.parentElement.nextElementSibling;
		if (feedback && feedback.classList.contains('invalid-feedback')) {
			feedback.textContent = message;
		}
	}

	function setFieldSuccessSede(field) {
		field.classList.remove('is-invalid');
		field.classList.add('is-valid');
		const feedback = field.parentElement.nextElementSibling;
		if (feedback && feedback.classList.contains('invalid-feedback')) {
			feedback.textContent = '';
		}
	}

	function validateFormSede() {
		const form = document.getElementById('formNuevaSede');
		const inputs = form.querySelectorAll('input[required]');
		let isValid = true;

		inputs.forEach(input => {
			if (input.type === 'email') {
				if (!validateEmailSede(input)) isValid = false;
			} else if (input.id === 'sedeTelefono') {
				if (!validatePhoneSede(input)) isValid = false;
			} else if (input.id === 'sedeNit') {
				if (!validateNITSede(input)) isValid = false;
			} else {
				if (!validateFieldSede(input)) isValid = false;
			}
		});

		return isValid;
	}

	//===========================================================================================================
	// GUARDAR NUEVA SEDE
	// FunciÛn principal para registrar la sede
	//===========================================================================================================
	function guardarNuevaSede() {
		if (!validateFormSede()) {
			Swal.fire({
				title: 'Formulario incompleto',
				text: 'Por favor complete todos los campos correctamente',
				icon: 'warning',
				confirmButtonText: 'Entendido',
				confirmButtonColor: '#f39c12'
			});
			return;
		}

		const formData = new FormData(document.getElementById('formNuevaSede'));

		// Crear objeto con los datos usando los nombres que espera el PHP
		const sedeData = new FormData();
		sedeData.append('sede-nit', formData.get('sede-nit'));
		sedeData.append('sede-nombre', formData.get('sede-nombre'));
		sedeData.append('sede-direccion', formData.get('sede-direccion'));
		sedeData.append('sede-telefono', formData.get('sede-telefono'));
		sedeData.append('sede-email', formData.get('sede-email'));
		sedeData.append('sede-id-representante', formData.get('sede-id-representante'));
		sedeData.append('sede-nom-representante', formData.get('sede-nom-representante'));
		sedeData.append('sucursal-id', formData.get('sucursal-id'));

		// AGREGAR TOKEN CSRF
		sedeData.append('csrf_token_sede', formData.get('csrf_token_sede'));

		// Mostrar loading con SweetAlert2
		Swal.fire({
			title: 'Guardando sede...',
			text: 'Por favor espere mientras procesamos la informaciÛn',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Enviar datos por AJAX
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: sedeData
		})
		.then(response => response.text())
		.then(data => {
			// Cerrar loading
			Swal.close();

			// Procesar respuesta del PHP
			try {
				// Si la respuesta es JSON directo
				const jsonData = JSON.parse(data);
				mostrarSweetAlert(jsonData);

				// Si fue exitoso, cerrar modal y recargar sedes
				if (jsonData.Tipo === 'success') {
					limpiarModalSedeYCerrar();
					regenerarTokenCSRF('input[name="csrf_token_sede"]', 'formNuevaSede');

					// Recargar contador y lista de sedes si estamos en tab de sedes
					if (empresaActualId) {
						// Si hay una funciÛn para cargar sedes de empresa
						if (typeof recargarSedesSucursal === 'function') {
							recargarSedesSucursal();
						}
					}
				}
			} catch (e) {
				// Si no es JSON, buscar el sweet_alert en el HTML
				mostrarSweetAlert(data);

				// Si contiene palabras de Èxito, cerrar modal
				if (data.includes('success') || data.includes('Sede registrada') || data.includes('exitoso')) {
					limpiarModalSedeYCerrar();
					regenerarTokenCSRF('input[name="csrf_token_sede"]', 'formNuevaSede');

					// Recargar contador y lista de sedes
					if (empresaActualId) {
						if (typeof recargarSedesSucursal === 'function') {
							recargarSedesSucursal();
						}
					}
				}
			}
		})
		.catch(error => {
			// Cerrar loading y mostrar error
			Swal.close();

			console.error('Error:', error);
			Swal.fire({
				title: 'Error de conexiÛn',
				text: 'No se pudo conectar con el servidor. Verifique su conexiÛn a internet.',
				icon: 'error',
				confirmButtonText: 'Reintentar',
				confirmButtonColor: '#e74c3c'
			});
		});
	}

	//===========================================================================================================
	// FUNCIONES AUXILIARES
	//===========================================================================================================

	// FunciÛn para limpiar y cerrar modal de sede
	function limpiarModalSedeYCerrar() {
		const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevaSede'));
		if (modal) modal.hide();

		limpiarFormularioSede();
	}

	// REGENERAR TOKEN CSRF para sede

	// LIMPIAR MODAL AL CERRARLO
	const modalSedeElement = document.getElementById('modalNuevaSede');
	if (modalSedeElement) {
		modalSedeElement.addEventListener('hidden.bs.modal', function() {
			limpiarFormularioSede();
		});
	}

	//===========================================================================================================
	// FUNCI”N PARA VER SEDES DE UNA SUCURSAL ESPECÕFICA
	// Se ejecuta desde el botÛn "Ver Sedes" en la lista de sucursales
	//===========================================================================================================

	function verSedesSucursal(sucursalIdEncriptado, nombreSucursal) {
		// Guardar datos de la sucursal actual globalmente
		window.sucursalActualId = sucursalIdEncriptado;
		window.sucursalActualNombre = nombreSucursal;

		// Mostrar loading
		Swal.fire({
			title: 'Cargando...',
			text: 'Obteniendo sedes de la sucursal',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Preparar datos para la peticiÛn
		const formData = new FormData();
		formData.append('accion', 'listar_sedes_sucursal');
		formData.append('sucursal_id', sucursalIdEncriptado);
		formData.append('filtro_nombre', ''); // Filtro vacÌo inicialmente
		formData.append('filtro_estado', ''); // Filtro vacÌo inicialmente
		formData.append('csrf_token_sedes', document.querySelector('input[name="csrf_token_list"]').value);

		// Enviar peticiÛn AJAX
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();

			if (data.status === 'success') {
				// Cambiar al tab de sedes en el modal actual
				const modalEmpresa = document.getElementById('modalVerEditarEmpresa');
				const isModalOpen = modalEmpresa && modalEmpresa.classList.contains('show');

				if (isModalOpen) {
					// Si el modal de empresa est· abierto, cambiar al tab de sedes
					cambiarATabSedes(data, nombreSucursal);
				}
			} else {
				Swal.fire({
					title: 'Error',
					text: data.Texto || 'No se pudieron cargar las sedes',
					icon: 'error'
				});
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error de conexiÛn',
				text: 'No se pudo conectar con el servidor',
				icon: 'error'
			});
		});
	}

	//===========================================================================================================
	// FUNCI”N PARA CAMBIAR AL TAB DE SEDES (CUANDO EL MODAL DE EMPRESA EST¡ ABIERTO)
	//===========================================================================================================

	function cambiarATabSedes(data, nombreSucursal) {
		// Activar el tab de sedes
		const tabSedes = document.getElementById('tab-sedes');
		const tabPaneSedes = document.getElementById('tabpane-sedes');

		// Remover active de todos los tabs
		document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
		document.querySelectorAll('.tab-pane').forEach(pane => {
			pane.classList.remove('show', 'active');
		});

		// Activar tab de sedes
		tabSedes.classList.add('active');
		tabPaneSedes.classList.add('show', 'active');

		// Actualizar contenido
		actualizarContenidoSedes(data, nombreSucursal);

		// Ocultar footer
		const modalFooter = document.getElementById('modalFooterInformacion');
		if (modalFooter) modalFooter.style.display = 'none';
	}


	//===========================================================================================================
	// FUNCI”N PARA ACTUALIZAR CONTENIDO DEL TAB DE SEDES
	//===========================================================================================================

	function actualizarContenidoSedes(data, nombreSucursal) {
		// Actualizar tÌtulo
		const nombreEmpresaSedes = document.getElementById('nombreEmpresaSedes');
		if (nombreEmpresaSedes) {
			nombreEmpresaSedes.textContent = empresaActualData ? empresaActualData.EmpresaNombre : 'la empresa';
		}

		// Actualizar nombre de sucursal
		const nombreSucursalSedes = document.getElementById('nombreSucursalSedes');
		if (nombreSucursalSedes) {
			nombreSucursalSedes.textContent = nombreSucursal;
		}

		// Actualizar contador en el tab
		const contadorSedes = document.getElementById('contadorSedes');
		if (contadorSedes) {
			contadorSedes.textContent = data.total_sedes || 0;
		}

		// Actualizar lista de sedes
		const listaSedes = document.getElementById('listaSedes');
		if (listaSedes) {
			listaSedes.innerHTML = data.html_sedes;
		}

		// Guardar datos de la sucursal para nuevas sedes
		window.sucursalActualData = {
			SucursalId: data.SucursalId,
			SucursalNombre: nombreSucursal,
			SucursalTelefono: data.sucursal_info.SucursalTelefono,
			SucursalEmail: data.sucursal_info.SucursalEmail,
			SucursalDireccion: data.sucursal_info.SucursalDireccion,
			SucursalIdRepresentante: data.sucursal_info.SucursalIdRepresentante,
			SucursalNomRepresentante: data.sucursal_info.SucursalNomRepresentante
			// agregar m·s datos si se necesita
		};
	}

	
	//===========================================================================================================
	// FUNCI”N PARA RECARGAR SEDES DESPU…S DE OPERACIONES
	//===========================================================================================================

	/*function recargarSedesSucursal() {
		if (window.sucursalActualId && window.sucursalActualNombre) {
			verSedesSucursal(window.sucursalActualId, window.sucursalActualNombre);
		}
	}*/
	//===========================================================================================================
	// FUNCI”N MEJORADA PARA RECARGAR SEDES CON FILTROS
	//===========================================================================================================
	function recargarSedesSucursal() {
		if (!window.sucursalActualId || !window.sucursalActualNombre) {
			console.warn('No hay sucursal actual definida para recargar sedes');
			return;
		}

		// Mostrar loading en la lista de sedes
		const listaSedes = document.getElementById('listaSedes');
		if (listaSedes) {
			listaSedes.innerHTML = `
				<div class="text-center py-5">
					<i class="bi bi-arrow-clockwise spin fa-2x text-muted mb-3"></i>
					<h6 class="text-muted">Actualizando sedes...</h6>
				</div>
			`;
		}

		// Obtener valores de filtros
		const filtroNombre = document.getElementById('filtroSedes')?.value || '';
		const filtroEstado = document.getElementById('filtroEstadoSedes')?.value || '';

		// Preparar datos para la peticiÛn
		const formData = new FormData();
		formData.append('accion', 'listar_sedes_sucursal');
		formData.append('sucursal_id', window.sucursalActualId);
		formData.append('filtro_nombre', filtroNombre);
		formData.append('filtro_estado', filtroEstado);
		formData.append('csrf_token_sedes', document.querySelector('input[name="csrf_token_list"]').value);

		// Enviar peticiÛn AJAX
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
				// Actualizar contenido usando la funciÛn existente
				actualizarContenidoSedes(data, window.sucursalActualNombre);
			} else {
				if (listaSedes) {
					listaSedes.innerHTML = `
						<div class="text-center py-5 text-danger">
							<i class="bi bi-exclamation-triangle fa-2x mb-3"></i>
							<h6>Error al actualizar sedes</h6>
							<p class="mb-0">${data.Texto || 'Error desconocido'}</p>
						</div>
					`;
				}
			}
		})
		.catch(error => {
			console.error('Error recargando sedes:', error);
			if (listaSedes) {
				listaSedes.innerHTML = `
					<div class="text-center py-5 text-danger">
						<i class="bi bi-wifi-off fa-2x mb-3"></i>
						<h6>Error de conexiÛn</h6>
						<p class="mb-0">No se pudo actualizar la lista de sedes</p>
					</div>
				`;
			}
		});
	}
	//===========================================================================================================
	// FUNCI”N PARA VER/EDITAR SEDE
	// Se ejecuta desde el botÛn "Ver/Editar" en la lista de sedes
	//===========================================================================================================
	
	function verSede(sedeIdEncriptado) {
		// Mostrar loading
		Swal.fire({
			title: 'Cargando...',
			text: 'Obteniendo datos de la sede',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'obtener_sede');
		formData.append('sede_id', sedeIdEncriptado);
		formData.append('csrf_token_obtener_sede', document.querySelector('input[name="csrf_token_list"]').value);

		// Enviar peticiÛn
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();

			if (data.status === 'success') {
				// Llenar el modal con los datos
				llenarModalSede(data.sede);

				// Mostrar el modal
				const modal = new bootstrap.Modal(document.getElementById('modalVerEditarSede'));
				modal.show();
			} else {
				mostrarSweetAlert(data);
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error',
				text: 'No se pudo cargar la informaciÛn de la sede',
				icon: 'error'
			});
		});
	}

	//===========================================================================================================
	// FUNCI”N PARA LLENAR EL MODAL CON DATOS DE LA SEDE
	//===========================================================================================================

	function llenarModalSede(sede) {
		// Campos de solo lectura
		document.getElementById('verSedeCodigo').value = sede.SedeCodigo || '';
		document.getElementById('verSedeEstado').value = sede.SedeEstado || '';
		document.getElementById('verSedeFechaRegistro').value = sede.SedeFechaRegistro || '';
		document.getElementById('verSedeSucursal').value = sede.SucursalNombre || '';

		// Campos editables
		document.getElementById('editSedeNit').value = sede.SedeNit || '';
		document.getElementById('editSedeNombre').value = sede.SedeNombre || '';
		document.getElementById('editSedeTelefono').value = sede.SedeTelefono || '';
		document.getElementById('editSedeEmail').value = sede.SedeEmail || '';
		document.getElementById('editSedeDireccion').value = sede.SedeDireccion || '';
		document.getElementById('editSedeIdRepresentante').value = sede.SedeIdRepresentante || '';
		document.getElementById('editSedeNomRepresentante').value = sede.SedeNomRepresentante || '';
		document.getElementById('sedeIdEditar').value = sede.SedeId;
		document.getElementById('tokenSedeEspecifico').value = sede.TokenSedeEspecifico;

		// Actualizar tÌtulo del modal
		document.getElementById('tituloSedeModal').textContent = `GestiÛn: ${sede.SedeNombre}`;

		// Limpiar validaciones previas
		const campos = document.querySelectorAll('#formVerEditarSede .form-control');
		campos.forEach(campo => {
			campo.classList.remove('is-valid', 'is-invalid');
		});

		console.log('Sede cargada:', {
			codigo: sede.SedeCodigo,
			nombre: sede.SedeNombre,
			sucursal: sede.SucursalNombre,
			tieneToken: sede.TokenSedeEspecifico ? 'SI' : 'NO'
		});
	}

	//===========================================================================================================
	// FUNCI”N PARA GUARDAR CAMBIOS DE SEDE
	//===========================================================================================================

	function guardarCambiosSede() {
		// Validar formulario
		if (!validarFormularioEdicionSede()) {
			Swal.fire({
				title: 'Formulario incompleto',
				text: 'Por favor complete todos los campos correctamente',
				icon: 'warning'
			});
			return;
		}

		// Verificar que tenemos el token especÌfico
		const tokenEspecifico = document.getElementById('tokenSedeEspecifico').value;
		if (!tokenEspecifico) {
			Swal.fire({
				title: 'Error de seguridad',
				text: 'Token de sede no encontrado. Vuelve a cargar la sede.',
				icon: 'error'
			});
			return;
		}

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'actualizar_sede');
		formData.append('sede_id', document.getElementById('sedeIdEditar').value);
		formData.append('sede_nit', document.getElementById('editSedeNit').value);
		formData.append('sede_nombre', document.getElementById('editSedeNombre').value);
		formData.append('sede_telefono', document.getElementById('editSedeTelefono').value);
		formData.append('sede_email', document.getElementById('editSedeEmail').value);
		formData.append('sede_direccion', document.getElementById('editSedeDireccion').value);
		formData.append('sede_id_representante', document.getElementById('editSedeIdRepresentante').value);
		formData.append('sede_nom_representante', document.getElementById('editSedeNomRepresentante').value);
		formData.append('csrf_token_editar_sede', document.querySelector('input[name="csrf_token_editar_sede"]').value);
		formData.append('token_sede_especifico', document.querySelector('input[name="tokenSedeEspecifico"]').value);

		// Mostrar loading
		Swal.fire({
			title: 'Guardando...',
			text: 'Actualizando informaciÛn de la sede',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Enviar datos
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();
			mostrarSweetAlert(data);

			if (data.Tipo === 'success') {
				// Cerrar modal
				const modal = bootstrap.Modal.getInstance(document.getElementById('modalVerEditarSede'));
				modal.hide();

				// Limpiar token especÌfico
				document.getElementById('tokenSedeEspecifico').value = '';

				// Regenerar token
				regenerarTokenCSRF('input[name="csrf_token_editar_sede"]', 'editarSede');

				// Recargar lista de sedes si estamos en el contexto correcto
				if (typeof recargarSedesSucursal === 'function') {
					recargarSedesSucursal();
				}
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error',
				text: 'No se pudieron guardar los cambios',
				icon: 'error'
			});
		});
	}

	//===========================================================================================================
	// VALIDACIONES PARA EDICI”N DE SEDE
	//===========================================================================================================

	function validarFormularioEdicionSede() {
		const campos = [
			{ id: 'editSedeNit', tipo: 'numero' },
			{ id: 'editSedeNombre', tipo: 'texto' },
			{ id: 'editSedeTelefono', tipo: 'telefono' },
			{ id: 'editSedeEmail', tipo: 'email' },
			{ id: 'editSedeDireccion', tipo: 'texto' },
			{ id: 'editSedeIdRepresentante', tipo: 'numero' },
			{ id: 'editSedeNomRepresentante', tipo: 'texto' }
		];

		let esValido = true;

		campos.forEach(campo => {
			const elemento = document.getElementById(campo.id);
			const valor = elemento.value.trim();

			// Limpiar estilos previos
			elemento.classList.remove('is-invalid', 'is-valid');

			if (valor === '') {
				elemento.classList.add('is-invalid');
				esValido = false;
			} else if (campo.tipo === 'email') {
				const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if (!emailRegex.test(valor)) {
					elemento.classList.add('is-invalid');
					esValido = false;
				} else {
					elemento.classList.add('is-valid');
				}
			} else if (campo.tipo === 'telefono') {
				const telefonoRegex = /^[0-9]{7,15}$/;
				if (!telefonoRegex.test(valor)) {
					elemento.classList.add('is-invalid');
					esValido = false;
				} else {
					elemento.classList.add('is-valid');
				}
			} else {
				elemento.classList.add('is-valid');
			}
		});

		return esValido;
	}

	//===========================================================================================================
	// LIMPIAR MODAL AL CERRARLO
	//===========================================================================================================

	// Event listener para limpiar modal al cerrarlo
	document.addEventListener('DOMContentLoaded', function() {
		const modalEditarSedeElement = document.getElementById('modalVerEditarSede');
		if (modalEditarSedeElement) {
			modalEditarSedeElement.addEventListener('hidden.bs.modal', function() {
				// Limpiar validaciones
				const campos = document.querySelectorAll('#formVerEditarSede .form-control');
				campos.forEach(campo => {
					campo.classList.remove('is-valid', 'is-invalid');
				});

				// Limpiar token especÌfico
				document.getElementById('tokenSedeEspecifico').value = '';
			});
		}
	});

	//===========================================================================================================
	// FUNCI”N PARA ELIMINAR SEDE
	//===========================================================================================================

	function eliminarSede(sedeIdEncriptado, sedeNombre) {
		Swal.fire({
			title: 'øEst·s seguro?',
			text: `øDeseas eliminar la sede "${sedeNombre}"?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'SÌ, eliminar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				ejecutarEliminacionSede(sedeIdEncriptado, sedeNombre);
			}
		});
	}

	function ejecutarEliminacionSede(sedeIdEncriptado, sedeNombre) {
		const formData = new FormData();
		formData.append('accion', 'eliminar_sede');
		formData.append('sede_id', sedeIdEncriptado);
		formData.append('csrf_token_eliminar_sede', document.querySelector('input[name="csrf_token_list"]').value);

		Swal.fire({
			title: 'Eliminando...',
			text: 'Por favor espere',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();
			mostrarSweetAlert(data);

			if (data.Tipo === 'success') {
				// Cerrar modal si est· abierto
				const modal = bootstrap.Modal.getInstance(document.getElementById('modalVerEditarSede'));
				if (modal) modal.hide();

				// Recargar lista de sedes
				if (typeof recargarSedesSucursal === 'function') {
					recargarSedesSucursal();
				}
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error',
				text: 'No se pudo eliminar la sede',
				icon: 'error'
			});
		});
	}

	//===========================================================================================================
	// GESTI”N DE ESTADOS DE SEDE CON DROPDOWN
	//===========================================================================================================

	// Event listener para cambios de estado de SEDES (usar delegaciÛn de eventos)
	document.addEventListener('click', function(e) {
		if (e.target.classList.contains('cambiar-estado-sede')) {
			e.preventDefault();

			const sedeId = e.target.getAttribute('data-sede-id');
			const sedeNombre = e.target.getAttribute('data-sede-nombre');
			const nuevoEstado = e.target.getAttribute('data-nuevo-estado');
			const estadoActual = e.target.closest('.dropdown').querySelector('.estado-dropdown').getAttribute('data-estado-actual');

			mostrarModalCambioEstadoSede(sedeId, sedeNombre, estadoActual, nuevoEstado);
		}
	});

	// FUNCI”N PARA MOSTRAR MODAL DE CAMBIO DE ESTADO DE SEDE
	function mostrarModalCambioEstadoSede(sedeId, sedeNombre, estadoActual, nuevoEstado) {
		// ConfiguraciÛn de estados (igual que empresas y sucursales)
		const configuracionEstados = {
			'Activo': { 
				color: 'success', 
				icono: 'bi-check-circle-fill', 
				descripcion: 'La sede estar· completamente operativa y podr· realizar todas sus actividades normalmente.',
				colorBtn: '#28a745'
			},
			'Inactivo': { 
				color: 'warning', 
				icono: 'bi-pause-circle-fill', 
				descripcion: 'La sede ser· temporalmente desactivada. Sus operaciones quedar·n suspendidas.',
				colorBtn: '#ffc107'
			},
			'Suspendido': { 
				color: 'danger', 
				icono: 'bi-x-circle-fill', 
				descripcion: 'La sede ser· suspendida por completo. No podr· realizar ninguna actividad.',
				colorBtn: '#dc3545'
			}
		};

		const config = configuracionEstados[nuevoEstado];

		// Llenar datos en el modal (reutilizar el mismo modal de empresas)
		document.getElementById('empresaIdCambioEstado').value = sedeId;
		document.getElementById('nuevoEstadoCambio').value = nuevoEstado;
		document.getElementById('nombreEmpresaCambio').textContent = sedeNombre;
		document.getElementById('estadoActualCambio').textContent = estadoActual;
		document.getElementById('motivoCambioEstado').value = '';

		// Configurar informaciÛn del nuevo estado
		const infoCard = document.getElementById('infoCambioEstado');
		const icono = document.getElementById('iconoNuevoEstado');
		const titulo = document.getElementById('tituloNuevoEstado');
		const descripcion = document.getElementById('descripcionNuevoEstado');
		const btnConfirmar = document.getElementById('btnConfirmarCambio');

		// Aplicar configuraciÛn visual
		infoCard.className = `estado-info-card ${config.color}`;
		icono.className = `bi ${config.icono} icono-estado-grande text-${config.color}`;
		titulo.textContent = `Cambiar a ${nuevoEstado}`;
		descripcion.textContent = config.descripcion;
		btnConfirmar.className = `btn btn-${config.color}`;
		btnConfirmar.style.backgroundColor = config.colorBtn;
		btnConfirmar.innerHTML = `<i class="bi bi-check-circle me-1"></i>Cambiar a ${nuevoEstado}`;

		// MARCAR QUE ES UNA SEDE para el procesamiento
		btnConfirmar.setAttribute('data-tipo-entidad', 'sede');

		// Limpiar validaciones previas
		const motivo = document.getElementById('motivoCambioEstado');
		motivo.classList.remove('is-valid', 'is-invalid');

		// Mostrar modal (reutilizar el de empresas)
		const modal = new bootstrap.Modal(document.getElementById('modalCambioEstado'));
		modal.show();

		// Enfocar en el textarea
		setTimeout(() => {
			motivo.focus();
		}, 500);
	}

	// FUNCI”N PARA EJECUTAR EL CAMBIO DE ESTADO DE SEDE
	function ejecutarCambioEstadoSede(sedeId, nuevoEstado, motivo) {
		// Cerrar modal primero
		const modal = bootstrap.Modal.getInstance(document.getElementById('modalCambioEstado'));
		modal.hide();

		// Mostrar loading
		Swal.fire({
			title: `Cambiando a ${nuevoEstado}...`,
			text: 'Por favor espere mientras se actualiza el estado de la sede',
			allowOutsideClick: false,
			allowEscapeKey: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});

		// Preparar datos
		const formData = new FormData();
		formData.append('accion', 'cambiar_estado_sede');
		formData.append('sede_id', sedeId);
		formData.append('nuevo_estado', nuevoEstado);
		formData.append('motivo_cambio', motivo);
		formData.append('csrf_token_estado_sede', document.querySelector('input[name="csrf_token_list"]').value);

		// Enviar peticiÛn
		fetch('../ajax/App_empresasAjax.php', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			Swal.close();

			// Mostrar resultado
			mostrarSweetAlert(data);

			if (data.Tipo === 'success') {
				// Regenerar token
				regenerarTokenCSRF('input[name="csrf_token_list"]', 'listEmpresas');
				// Recargar lista de sedes si estamos en el contexto correcto
				if (typeof recargarSedesSucursal === 'function') {
					recargarSedesSucursal();
				}
			}
		})
		.catch(error => {
			Swal.close();
			console.error('Error:', error);
			Swal.fire({
				title: 'Error de conexiÛn',
				text: 'No se pudo cambiar el estado de la sede',
				icon: 'error'
			});
		});
	}

	//===========================================================================================================
	// FILTROS PARA SEDES
	// Funciones para filtrar sedes por nombre y estado
	//===========================================================================================================

	// Event listeners para filtros de sedes (mejorados)
	document.addEventListener('DOMContentLoaded', function() {
		// Filtro de b˙squeda con debounce para sedes
		let filtroSedesTimeout;
		const filtroSedes = document.getElementById('filtroSedes');
		if (filtroSedes) {
			filtroSedes.addEventListener('input', function() {
				clearTimeout(filtroSedesTimeout);
				filtroSedesTimeout = setTimeout(() => {
					if (window.sucursalActualId && window.sucursalActualNombre) {
						recargarSedesSucursal();
					}
				}, 500);
			});
		}

		// Filtro de estado para sedes
		const filtroEstadoSedes = document.getElementById('filtroEstadoSedes');
		if (filtroEstadoSedes) {
			filtroEstadoSedes.addEventListener('change', function() {
				if (window.sucursalActualId && window.sucursalActualNombre) {
					recargarSedesSucursal();
				}
			});
		}
	});

	//===========================================================================================================
	// FUNCI”N PARA LIMPIAR FILTROS DE SEDES
	//===========================================================================================================
	function limpiarFiltrosSedes() {
		const filtroSedes = document.getElementById('filtroSedes');
		const filtroEstadoSedes = document.getElementById('filtroEstadoSedes');

		if (filtroSedes) filtroSedes.value = '';
		if (filtroEstadoSedes) filtroEstadoSedes.value = '';

		if (window.sucursalActualId && window.sucursalActualNombre) {
			recargarSedesSucursal();
		}
	}

