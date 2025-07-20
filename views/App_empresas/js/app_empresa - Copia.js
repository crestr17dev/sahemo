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
		
		// Event listeners para filtros
		document.getElementById('shareempresa').addEventListener('input', filtrarEmpresas);
		document.getElementById('estadoempresa').addEventListener('change', filtrarEmpresas);
		
		// Cargar empresas al iniciar
		cargarEmpresas();

	});


	// FUNCIONES DE VALIDACIÃ“N
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
			setFieldError(field, 'Ingrese un email vÃ¡lido');
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
			setFieldError(field, 'Ingrese un telÃ©fono vÃ¡lido (7-15 dÃ­gitos)');
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
			setFieldError(field, 'El NIT debe tener entre 8 y 11 dÃ­gitos');
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

	// FUNCIÃ“N PARA MOSTRAR SWEETALERT2
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
					// Si no es JSON, mostrar mensaje genÃ©rico
					alertData = {
						Titulo: 'InformaciÃ³n',
						Texto: data,
						Tipo: 'info'
					};
				}
			} catch (e) {
				alertData = {
					Titulo: 'InformaciÃ³n',
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
			title: alertData.Titulo || 'InformaciÃ³n',
			text: alertData.Texto || 'OperaciÃ³n completada',
			icon: tipoIcono[alertData.Tipo] || 'info',
			confirmButtonText: 'Aceptar',
			confirmButtonColor: '#3085d6'
		});
	}

	// FUNCIÃ“N PRINCIPAL DE GUARDAR - MODIFICADA PARA SWEETALERT2
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
			text: 'Por favor espere mientras procesamos la informaciÃ³n',
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
					// Opcional: recargar la pÃ¡gina o actualizar la tabla
					setTimeout(() => {
						location.reload();
					}, 1500);
				}
			} catch (e) {
				// Si no es JSON, buscar el sweet_alert en el HTML
				mostrarSweetAlert(data);
				
				// Si contiene palabras de Ã©xito, cerrar modal
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
				title: 'Error de conexiÃ³n',
				text: 'No se pudo conectar con el servidor. Verifique su conexiÃ³n a internet.',
				icon: 'error',
				confirmButtonText: 'Reintentar',
				confirmButtonColor: '#e74c3c'
			});
		});
	}

	// FUNCIÃ“N PARA LIMPIAR Y CERRAR MODAL (evita duplicaciÃ³n)
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

	// FUNCIÓN REUTILIZABLE PARA REGENERAR TOKENS CSRF
	function regenerarTokenCSRF(selector, key) {
		// Validación básica de parámetros
		if (!selector || !key) {
			console.error('Faltan parámetros requeridos: selector y key');
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
					console.error(`No se encontró el elemento con selector: ${selector}`);
				}
			} else {
				console.error('Respuesta inválida del servidor:', data);
			}
		})
		.catch(error => {
			console.error(`Error regenerando token ${key}:`, error);
		});
	}

	//===========================================================================================================
	// FUNCIÃ“N PRINCIPAL PARA CARGAR EMPRESAS
	//===========================================================================================================
	
	// Variable global para recordar quÃ© vista estÃ¡ activa
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
					<p class="text-muted">AquÃ­ se cargan las empresas disponibles</p>
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

				// Actualizar estadÃ­sticas
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
							Error de conexiÃ³n. Intenta nuevamente.
						</td>
					</tr>`;
			}

			Swal.fire({
				title: 'Error de conexiÃ³n',
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
	// FUNCIÃ“N PARA FILTRAR EMPRESAS (CON DEBOUNCE)
	//===========================================================================================================
	let filtroTimeout;
	function filtrarEmpresas() {
		clearTimeout(filtroTimeout);
		filtroTimeout = setTimeout(() => {
			cargarEmpresas(1); // Siempre volver a pÃ¡gina 1 al filtrar
		}, 500); // Esperar 500ms despuÃ©s de que el usuario deje de escribir
	}

	//===========================================================================================================
	// FUNCIÃ“N PARA CAMBIAR DE PÃGINA
	//===========================================================================================================
	function cargarPagina(pagina) {
		cargarEmpresas(pagina);
	}

	//===========================================================================================================
	// FUNCIÃ“N PARA CAMBIAR VISTA
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

	// FUNCIÃ“N PARA ELIMINAR EMPRESA
	function eliminarEmpresa(codigoEmpresa, nombreEmpresa, paginaactual) {
		Swal.fire({
			title: 'Â¿EstÃ¡s seguro?',
			text: `Â¿Deseas eliminar la empresa "${nombreEmpresa}"?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'SÃ­, eliminar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				eliminarEmpresaAjax(codigoEmpresa, nombreEmpresa);
			}
		});
	}

	// FUNCIÃ“N AJAX PARA ELIMINAR EMPRESA
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
				// Recargar listado despuÃ©s de eliminar
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
	// FUNCIÃ“N SIMPLE PARA VER/EDITAR EMPRESA (UN SOLO BOTÃ“N)
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

		// Enviar peticiÃ³n
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
				// Resetear tabs al tab de informaciÃ³n
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
				text: 'No se pudo cargar la informaciÃ³n',
				icon: 'error'
			});
		});
	}

	// FUNCIÃ“N PARA LLENAR EL MODAL CON LOS DATOS
	function llenarModalEmpresa(empresa) {
		llenarModalEmpresaConTabs(empresa);
	}

	// FUNCIÃ“N PARA GUARDAR CAMBIOS
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
		
		// Verificar que tenemos el token especÃ­fico
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
			text: 'Actualizando informaciÃ³n de la empresa',
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
				
				// Limpiar token especÃ­fico de la sesiÃ³n
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

	// FUNCIÃ“N PARA VALIDAR FORMULARIO
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
	// ACTUALIZAR LAS FUNCIONES EXISTENTES PARA QUE USEN LA MISMA FUNCIÃ“N
	function editarEmpresa(empresaId) {
		// Simplemente redirigir a la funciÃ³n principal
		verEmpresa(empresaId);
	}
	
	//===========================================================================================================
	// GESTIÃ“N DE ESTADOS CON DROPDOWN 
	//===========================================================================================================

	// Event listener para cambios de estado (usar delegaciÃ³n de eventos)
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

	// FUNCIÃ“N PARA MOSTRAR MODAL DE CAMBIO DE ESTADO
	function mostrarModalCambioEstado(empresaId, empresaNombre, estadoActual, nuevoEstado) {

		// ConfiguraciÃ³n de estados
		const configuracionEstados = {
			'Activo': { 
				color: 'success', 
				icono: 'bi-check-circle-fill', 
				descripcion: 'La empresa estarÃ¡ completamente operativa y podrÃ¡ realizar todas sus actividades normalmente.',
				colorBtn: '#28a745'
			},
			'Inactivo': { 
				color: 'warning', 
				icono: 'bi-pause-circle-fill', 
				descripcion: 'La empresa serÃ¡ temporalmente desactivada. Sus operaciones quedarÃ¡n suspendidas.',
				colorBtn: '#ffc107'
			},
			'Suspendido': { 
				color: 'danger', 
				icono: 'bi-x-circle-fill', 
				descripcion: 'La empresa serÃ¡ suspendida por completo. No podrÃ¡ realizar ninguna actividad.',
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

		// Configurar informaciÃ³n del nuevo estado
		const infoCard = document.getElementById('infoCambioEstado');
		const icono = document.getElementById('iconoNuevoEstado');
		const titulo = document.getElementById('tituloNuevoEstado');
		const descripcion = document.getElementById('descripcionNuevoEstado');
		const btnConfirmar = document.getElementById('btnConfirmarCambio');

		// Aplicar configuraciÃ³n visual
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
	
	// FUNCIÓN PARA CONFIRMAR CAMBIO DE ESTADO (EMPRESAS, SUCURSALES Y SEDES)
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

		// Ejecutar cambio según el tipo
		if (tipoEntidad === 'sede') {
			ejecutarCambioEstadoSede(entidadId, nuevoEstado, motivoTexto);
		} else if (tipoEntidad === 'sucursal') {
			ejecutarCambioEstadoSucursal(entidadId, nuevoEstado, motivoTexto);
		} else {
			ejecutarCambioEstado(entidadId, nuevoEstado, motivoTexto);
		}
	}

	// FUNCIÃ“N PARA EJECUTAR EL CAMBIO DE ESTADO
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

		// Enviar peticiÃ³n
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
				title: 'Error de conexiÃ³n',
				text: 'No se pudo cambiar el estado de la empresa',
				icon: 'error'
			});
		});
	}

	// VALIDACIÃ“N EN TIEMPO REAL DEL MOTIVO
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
	// EXPORTACIÃ“N A EXCEL
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

    // Realizar peticiÃ³n
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
        
        // Limpiar despuÃ©s de un momento
        setTimeout(() => {
            document.body.removeChild(enlace);
            window.URL.revokeObjectURL(url);
        }, 100);

        // Mostrar Ã©xito
        Swal.fire({
            title: 'âœ… Excel generado',
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
	// GESTIÃ“N DE TABS EN EL MODAL DE EMPRESA
	// Funciones para manejar la navegaciÃ³n por tabs y cargar contenido dinÃ¡mico
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

	// FUNCIÃ“N para manejar el cambio de tabs
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
	

	// FUNCIÃ“N para llenar el modal con datos (actualizada para tabs)
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

		// NUEVO: Actualizar tÃ­tulo del modal y nombres en tabs
		document.getElementById('tituloEmpresaModal').textContent = `GestiÃ³n de Empresa: ${empresa.EmpresaNombre}`;
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

	// FUNCIÃ“N para resetear tabs al tab principal
	function resetearTabs() {
		// Activar el primer tab (InformaciÃ³n)
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

