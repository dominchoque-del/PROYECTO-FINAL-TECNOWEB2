const API_URL = 'http://127.0.0.1:8000/api';

window.currentUser = null;
window.token = localStorage.getItem('token');

async function apiRequest(endpoint, method = 'GET', data = null) {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };
    
    if (window.token) {
        headers['Authorization'] = `Bearer ${window.token}`;
    }
    
    const config = {
        method,
        headers
    };
    
    if (data) {
        config.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(`${API_URL}${endpoint}`, config);
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.mensaje || result.message || 'Error en la solicitud');
        }
        
        return result;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

function showAlert(message, type = 'error') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertDiv.style.display = 'block';
    return alertDiv;
}

function showLoading() {
    return '<div class="loading"><i class="fas fa-spinner"></i> Cargando...</div>';
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('es-ES', { 
        style: 'currency', 
        currency: 'USD' 
    }).format(amount || 0);
}

function getEstadoBadge(estado) {
    const badges = {
        'programado': 'badge-info',
        'abordando': 'badge-warning',
        'en_vuelo': 'badge-primary',
        'aterrizado': 'badge-success',
        'cancelado': 'badge-danger',
        'confirmada': 'badge-success',
        'pendiente': 'badge-warning',
        'cancelada': 'badge-danger',
        'activo': 'badge-success',
        'mantenimiento': 'badge-warning',
        'retirado': 'badge-danger'
    };
    const badgeClass = badges[estado] || 'badge-info';
    return `<span class="badge ${badgeClass}">${estado}</span>`;
}

async function login(email, password) {
    try {
        const result = await apiRequest('/auth/login', 'POST', { email, password });
        
        window.token = result.token;
        window.currentUser = result.usuario;
        
        localStorage.setItem('token', window.token);
        localStorage.setItem('user', JSON.stringify(window.currentUser));
        
        showDashboard();
        return true;
    } catch (error) {
        throw error;
    }
}

async function registro(nombre, email, password) {
    try {
        const result = await apiRequest('/auth/registro', 'POST', { 
            nombre, 
            email, 
            password 
        });
        return result;
    } catch (error) {
        throw error;
    }
}

function logout() {
    window.token = null;
    window.currentUser = null;
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = 'index.html';
}

function checkAuth() {
    const userData = localStorage.getItem('user');
    if (window.token && userData) {
        window.currentUser = JSON.parse(userData);
        return true;
    }
    return false;
}

function showLogin() {
    document.querySelector('.login-container').style.display = 'flex';
    document.querySelector('.dashboard').style.display = 'none';
}

function showDashboard() {
    document.querySelector('.login-container').style.display = 'none';
    document.querySelector('.dashboard').style.display = 'block';
    
    if (window.currentUser) {
        document.querySelector('.user-name').textContent = window.currentUser.nombre;
        document.querySelector('.user-role').textContent = window.currentUser.rol || 'Usuario';
    }
    
    loadDashboardData();
}

async function loadDashboardData() {
    try {
        const [vuelosRes, reservasRes, navesRes, pasajerosRes] = await Promise.all([
            apiRequest('/vuelos'),
            apiRequest('/reservas'),
            apiRequest('/naves'),
            apiRequest('/pasajeros')
        ]);
        
        const vuelos = vuelosRes.data || [];
        const reservas = reservasRes.data || [];
        const naves = navesRes.data || [];
        const pasajeros = pasajerosRes.data || [];
        
        document.getElementById('total-vuelos').textContent = vuelos.length;
        document.getElementById('total-reservas').textContent = reservas.length;
        document.getElementById('total-naves').textContent = naves.filter(n => n.estado === 'activo').length;
        document.getElementById('total-pasajeros').textContent = pasajeros.length;
        
        const vuelosActivos = vuelos.filter(v => v.estado === 'en_vuelo' || v.estado === 'programado').length;
        const ingresosTotales = reservas.reduce((sum, r) => sum + (r.total || 0), 0);
        
        document.getElementById('vuelos-activos').textContent = vuelosActivos;
        document.getElementById('ingresos-totales').textContent = formatCurrency(ingresosTotales);
        
        renderRecentActivity(vuelos, reservas);
    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

function renderRecentActivity(vuelos, reservas) {
    const container = document.getElementById('recent-activity');
    if (!container) return;
    
    const activities = [];
    
    vuelos.slice(0, 3).forEach(v => {
        activities.push({
            icon: 'fa-plane',
            color: '#00b4d8',
            title: `Vuelo ${v.numero_vuelo}`,
            description: `Destino: ${v.destino}`,
            time: formatDate(v.fecha_salida)
        });
    });
    
    reservas.slice(0, 3).forEach(r => {
        activities.push({
            icon: 'fa-ticket-alt',
            color: '#06d6a0',
            title: `Reserva ${r.codigo_reserva}`,
            description: `${r.cantidad_pasajeros} pasajeros`,
            time: formatDate(r.created_at)
        });
    });
    
    container.innerHTML = activities.map(a => `
        <li>
            <div class="activity-icon" style="background: ${a.color}20; color: ${a.color}">
                <i class="fas ${a.icon}"></i>
            </div>
            <div>
                <strong>${a.title}</strong>
                <p>${a.description}</p>
                <small>${a.time}</small>
            </div>
        </li>
    `).join('');
}

async function loadVuelos() {
    try {
        const result = await apiRequest('/vuelos');
        const vuelos = result.data || [];
        
        const container = document.getElementById('vuelos-list');
        container.innerHTML = showLoading();
        
        if (vuelos.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-plane-slash"></i>
                    <h4>No hay vuelos registrados</h4>
                    <p>Crea el primer vuelo para comenzar</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Vuelo</th>
                        <th>Aerolínea</th>
                        <th>Destino</th>
                        <th>Salida</th>
                        <th>Llegada</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${vuelos.map(v => `
                        <tr>
                            <td><strong>${v.numero_vuelo || '-'}</strong></td>
                            <td>${v.aerolinea?.nombre || '-'}</td>
                            <td>${v.destino}</td>
                            <td>${formatDate(v.fecha_salida)}</td>
                            <td>${formatDate(v.fecha_llegada)}</td>
                            <td>${formatCurrency(v.precio_base)}</td>
                            <td>${getEstadoBadge(v.estado)}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-danger" onclick="deleteVuelo(${v.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        document.getElementById('vuelos-list').innerHTML = `
            <div class="alert alert-error">Error al cargar vuelos: ${error.message}</div>
        `;
    }
}

async function deleteVuelo(id) {
    if (!confirm('¿Estás seguro de cancelar este vuelo?')) return;
    
    try {
        await apiRequest(`/vuelos/${id}`, 'DELETE');
        loadVuelos();
    } catch (error) {
        alert('Error al cancelar vuelo: ' + error.message);
    }
}

async function loadReservas() {
    try {
        const result = await apiRequest('/reservas');
        const reservas = result.data || [];
        
        const container = document.getElementById('reservas-list');
        container.innerHTML = showLoading();
        
        if (reservas.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-ticket-alt"></i>
                    <h4>No hay reservas</h4>
                    <p>Crea la primera reserva</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Usuario</th>
                        <th>Vuelo</th>
                        <th>Pasajeros</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${reservas.map(r => `
                        <tr>
                            <td><strong>${r.codigo_reserva}</strong></td>
                            <td>${r.usuario?.nombre || '-'}</td>
                            <td>${r.vuelo?.numero_vuelo || '-'}</td>
                            <td>${r.cantidad_pasajeros}</td>
                            <td>${formatCurrency(r.total)}</td>
                            <td>${getEstadoBadge(r.estado)}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-danger" onclick="cancelReserva(${r.id})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        document.getElementById('reservas-list').innerHTML = `
            <div class="alert alert-error">Error al cargar reservas: ${error.message}</div>
        `;
    }
}

async function cancelReserva(id) {
    if (!confirm('¿Estás seguro de cancelar esta reserva?')) return;
    
    try {
        await apiRequest(`/reservas/${id}`, 'DELETE');
        loadReservas();
    } catch (error) {
        alert('Error al cancelar reserva: ' + error.message);
    }
}

async function loadPasajeros() {
    try {
        const result = await apiRequest('/pasajeros');
        const pasajeros = result.data || [];
        
        const container = document.getElementById('pasajeros-list');
        container.innerHTML = showLoading();
        
        if (pasajeros.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h4>No hay pasajeros</h4>
                    <p>Registra el primer pasajeros</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Email</th>
                        <th>Vuelo</th>
                        <th>Clase</th>
                        <th>Asiento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${pasajeros.map(p => `
                        <tr>
                            <td><strong>${p.nombre_completo}</strong></td>
                            <td>${p.dni || '-'}</td>
                            <td>${p.email || '-'}</td>
                            <td>${p.vuelo?.numero_vuelo || '-'}</td>
                            <td>${p.clase || '-'}</td>
                            <td>${p.asiento || '-'}</td>
                            <td>${getEstadoBadge(p.estado_reserva)}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-danger" onclick="deletePasajero(${p.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        document.getElementById('pasajeros-list').innerHTML = `
            <div class="alert alert-error">Error al cargar pasajeros: ${error.message}</div>
        `;
    }
}

async function deletePasajero(id) {
    if (!confirm('¿Estás seguro de eliminar este pasajero?')) return;
    
    try {
        await apiRequest(`/pasajeros/${id}`, 'DELETE');
        loadPasajeros();
    } catch (error) {
        alert('Error al eliminar pasajero: ' + error.message);
    }
}

async function loadNaves() {
    try {
        const result = await apiRequest('/naves');
        const naves = result.data || [];
        
        const container = document.getElementById('naves-list');
        container.innerHTML = showLoading();
        
        if (naves.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-plane"></i>
                    <h4>No hay naves</h4>
                    <p>Registra la primera aeronave</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Modelo</th>
                        <th>Aerolínea</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${naves.map(n => `
                        <tr>
                            <td><strong>${n.matricula}</strong></td>
                            <td>${n.modelo}</td>
                            <td>${n.aerolinea?.nombre || '-'}</td>
                            <td>${n.capacidad} pasajeros</td>
                            <td>${getEstadoBadge(n.estado)}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-danger" onclick="deleteNave(${n.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        document.getElementById('naves-list').innerHTML = `
            <div class="alert alert-error">Error al cargar naves: ${error.message}</div>
        `;
    }
}

async function deleteNave(id) {
    if (!confirm('¿Estás seguro de eliminar esta nave?')) return;
    
    try {
        await apiRequest(`/naves/${id}`, 'DELETE');
        loadNaves();
    } catch (error) {
        alert('Error al eliminar nave: ' + error.message);
    }
}

async function loadRutas() {
    try {
        const result = await apiRequest('/rutas');
        const rutas = result.data || [];
        
        const container = document.getElementById('rutas-list');
        container.innerHTML = showLoading();
        
        if (rutas.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-route"></i>
                    <h4>No hay rutas</h4>
                    <p>Crea la primera ruta</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Distancia</th>
                        <th>Duración</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${rutas.map(r => `
                        <tr>
                            <td><strong>${r.codigo_origen} → ${r.codigo_destino}</strong></td>
                            <td>${r.origen}</td>
                            <td>${r.destino}</td>
                            <td>${r.distancia_km || '-'} km</td>
                            <td>${r.duracion_min || '-'} min</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-danger" onclick="deleteRuta(${r.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        document.getElementById('rutas-list').innerHTML = `
            <div class="alert alert-error">Error al cargar rutas: ${error.message}</div>
        `;
    }
}

async function deleteRuta(id) {
    if (!confirm('¿Estás seguro de eliminar esta ruta?')) return;
    
    try {
        await apiRequest(`/rutas/${id}`, 'DELETE');
        loadRutas();
    } catch (error) {
        alert('Error al eliminar ruta: ' + error.message);
    }
}

async function loadAerolineas() {
    try {
        const result = await apiRequest('/aerolineas');
        const aerolineas = result.data || [];
        
        const container = document.getElementById('aerolineas-list');
        container.innerHTML = showLoading();
        
        if (aerolineas.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-building"></i>
                    <h4>No hay aerolíneas</h4>
                    <p>Registra la primera aerolínea</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>País</th>
                        <th>Vuelos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${aerolineas.map(a => `
                        <tr>
                            <td><strong>${a.nombre}</strong></td>
                            <td>${a.codigo || '-'}</td>
                            <td>${a.pais || '-'}</td>
                            <td>${a.vuelos?.length || 0}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-danger" onclick="deleteAerolinea(${a.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        document.getElementById('aerolineas-list').innerHTML = `
            <div class="alert alert-error">Error al cargar aerolíneas: ${error.message}</div>
        `;
    }
}

async function deleteAerolinea(id) {
    if (!confirm('¿Estás seguro de eliminar esta aerolínea?')) return;
    
    try {
        await apiRequest(`/aerolineas/${id}`, 'DELETE');
        loadAerolineas();
    } catch (error) {
        alert('Error al eliminar aerolínea: ' + error.message);
    }
}

let monitoreoInterval = null;

async function loadMonitoreo() {
    try {
        const result = await apiRequest('/monitoreo');
        const monitoreos = result.data || [];
        
        const container = document.getElementById('monitoreo-list');
        
        if (monitoreos.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-satellite-dish"></i>
                    <h4>No hay datos de monitoreo</h4>
                    <p>Los datos de monitoreo aparecerán aquí</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Vuelo</th>
                        <th>Aerolínea</th>
                        <th>Latitud</th>
                        <th>Longitud</th>
                        <th>Altitud</th>
                        <th>Velocidad</th>
                        <th>Estado</th>
                        <th>Última Actualización</th>
                    </tr>
                </thead>
                <tbody>
                    ${monitoreos.map(m => `
                        <tr>
                            <td><strong>${m.vuelo?.numero_vuelo || '-'}</strong></td>
                            <td>${m.vuelo?.aerolinea?.nombre || '-'}</td>
                            <td>${m.latitud?.toFixed(4) || '-'}</td>
                            <td>${m.longitud?.toFixed(4) || '-'}</td>
                            <td>${m.altitud_metros || '-'} m</td>
                            <td>${m.velocidad_kmh || '-'} km/h</td>
                            <td>${getEstadoBadge(m.estado_actual)}</td>
                            <td>${formatDate(m.registrado_en)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        console.error('Error loading monitoreo:', error);
    }
}

function startMonitoreoRealTime() {
    loadMonitoreo();
    monitoreoInterval = setInterval(loadMonitoreo, 5000);
}

function stopMonitoreoRealTime() {
    if (monitoreoInterval) {
        clearInterval(monitoreoInterval);
        monitoreoInterval = null;
    }
}

async function loadAerolineasForSelect() {
    try {
        const result = await apiRequest('/aerolineas');
        const aerolineas = result.data || [];
        
        const select = document.getElementById('vuelo-aerolinea');
        if (select) {
            select.innerHTML = '<option value="">Seleccionar...</option>' + 
                aerolineas.map(a => `<option value="${a.id}">${a.nombre}</option>`).join('');
        }
        
        return aerolineas;
    } catch (error) {
        console.error('Error loading aerolineas:', error);
        return [];
    }
}

async function loadVuelosForSelect() {
    try {
        const result = await apiRequest('/vuelos');
        const vuelos = result.data || [];
        
        const selects = ['reserva-vuelo', 'pasajero-vuelo'];
        selects.forEach(id => {
            const select = document.getElementById(id);
            if (select) {
                select.innerHTML = '<option value="">Seleccionar...</option>' + 
                    vuelos.map(v => `<option value="${v.id}">${v.numero_vuelo} - ${v.destino}</option>`).join('');
            }
        });
        
        return vuelos;
    } catch (error) {
        console.error('Error loading vuelos:', error);
        return [];
    }
}

async function loadUsuariosForSelect() {
    try {
        const result = await apiRequest('/usuarios');
        const usuarios = result.data || [];
        
        const select = document.getElementById('reserva-usuario');
        if (select) {
            select.innerHTML = '<option value="">Seleccionar...</option>' + 
                usuarios.map(u => `<option value="${u.id}">${u.nombre}</option>`).join('');
        }
        
        return usuarios;
    } catch (error) {
        console.error('Error loading usuarios:', error);
        return [];
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const loginContainer = document.querySelector('.login-container');
    const dashboard = document.querySelector('.dashboard');
    
    if (loginContainer && dashboard) {
        if (checkAuth()) {
            showDashboard();
        } else {
            showLogin();
        }
        
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                document.querySelectorAll('.sidebar-menu a').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const page = this.getAttribute('data-page');
                showPage(page);
            });
        });
        
        window.addEventListener('beforeunload', function() {
            stopMonitoreoRealTime();
        });
    }
});

function showPage(page) {
    document.querySelectorAll('.page').forEach(p => p.style.display = 'none');
    document.getElementById(`page-${page}`).style.display = 'block';
    
    switch(page) {
        case 'dashboard':
            loadDashboardData();
            break;
        case 'vuelos':
            loadVuelos();
            loadAerolineasForSelect();
            break;
        case 'reservas':
            loadReservas();
            loadVuelosForSelect();
            loadUsuariosForSelect();
            break;
        case 'pasajeros':
            loadPasajeros();
            loadVuelosForSelect();
            break;
        case 'naves':
            loadNaves();
            loadAerolineasForSelect();
            break;
        case 'rutas':
            loadRutas();
            break;
        case 'aerolineas':
            loadAerolineas();
            break;
        case 'monitoreo':
            startMonitoreo();
            break;
        case 'usuarios':
            loadUsuarios();
            break;
    }
}
