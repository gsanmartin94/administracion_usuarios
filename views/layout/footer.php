                                </div>
                                <div class="card-body">
                                </div>
                                </div>
                                </div>
                                </div>

                                </div>
                                </main>

                                <!--FOOTER-->

                                <footer class="footer">
                                	<div class="container-fluid">
                                		<div class="row text-muted">
                                			<div class="col-6 text-start">
                                				<p class="mb-0">
                                					<a class="text-muted" href="#" target="_blank"><strong>Unidad de Tecnologías de la Información y Comunicaciones</strong></a> &copy;
                                				</p>
                                			</div>
                                			<div class="col-6 text-end">
                                				<ul class="list-inline">
                                					<li class="list-inline-item">
                                						<a class="text-muted" href="https://pasaje.gob.ec/" target="_blank">Alcaldía</a>
                                					</li>
                                					<li class="list-inline-item">
                                						<a class="text-muted" href="https://pasaje.gob.ec/" target="_blank">Información</a>
                                					</li>
                                				</ul>
                                			</div>
                                		</div>
                                	</div>
                                </footer>
                                </div>
                                </div>

                                <!-- MODAL UPDATE-->
                                <div class="modal fade" id="cliente_update_password" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                	<div class="modal-dialog">
                                		<div class="modal-content">
                                			<div class="modal-header">
                                				<h4 class="modal-title w-100 text-center">Actualizar Password Usuario</h4>
                                				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                			</div>
                                			<div class="modal-body">
                                				<form id="formUsuarioUpdatePassword">
                                					<div class="row">
                                						<div class="col-12" style="display: none;">
                                							<label class="form-label">id</label>
                                							<input type="text" class="form-control" id="id_updatePasswordCliente" name="id_updatePasswordCliente" readonly />
                                						</div>
                                						<div class="col-6 mt-2">
                                							<label class="form-label">New Password</label>
                                							<input type="password" class="form-control" id="password1_update" value="***" name="password1_update" require />
                                						</div>
                                						<div class="col-6 mt-2">
                                							<label class="form-label">Confirm Password</label>
                                							<input type="password" class="form-control" id="password2_update" value="***" name="password2_update" require />
                                						</div>
                                					</div>
                                				</form>
                                			</div>
                                			<div class="modal-footer">
                                				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                				<button type="button" class="btn btn-primary btn-clienteUpdatePassword">Guardar</button>
                                			</div>
                                		</div>
                                	</div>
                                </div>

                                <script src="src/js/app.js"></script>
								<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
								<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
								<script src="src/js/Actions/footer.js"></script>
                                <script src="https://unpkg.com/feather-icons"></script>
                                <?php if (isset($_GET['login']) && $_GET['login'] === 'success'): ?>
                                	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                	<script>
                                		Swal.fire({
                                			icon: 'success',
                                			title: '¡Bienvenido!',
                                			text: 'Has iniciado sesión correctamente.',
                                			timer: 3000,
                                			showConfirmButton: false
                                		});
                                	</script>
                                <?php endif; ?>
                                <script>
                                	// Limpiar la ruta de 'success' de notificacion login
                                	if (window.location.search.includes("login=success")) {
                                		history.replaceState(null, "", window.location.pathname);
                                	}
                                </script>

                                </body>

                                </html>