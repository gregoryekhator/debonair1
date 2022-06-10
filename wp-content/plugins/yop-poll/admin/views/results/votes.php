<?php
$user_type_sort_order = 'asc';
$user_type_sort_order_display = 'desc';
$user_type_column_class = 'sortable';

$username_sort_order = 'asc';
$username_sort_order_display = 'desc';
$username_column_class = 'sortable';

$user_email_sort_order = 'asc';
$user_email_sort_order_display = 'desc';
$user_email_column_class = 'sortable';

$ipaddress_sort_order = 'asc';
$ipaddress_sort_order_display = 'desc';
$ipaddress_column_class = 'sortable';

$added_date_sort_order = 'asc';
$added_date_sort_order_display = 'desc';
$added_date_column_class = 'sortable';

switch ( $params['order_by'] ) {
    case 'user_type': {
        $user_type_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $user_type_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $user_type_column_class = 'sorted';
        break;
    }
    case 'username':{
        $username_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $username_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $username_column_class = 'sorted';
        break;
    }
    case 'user_email':{
        $user_email_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $user_email_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $user_email_column_class = 'sorted';
        break;
    }
    case 'ipaddress': {
        $ipaddress_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $ipaddress_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $ipaddress_column_class = 'sorted';
        break;
    }
    case 'added_date': {
        $added_date_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $added_date_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $added_date_column_class = 'sorted';
        break;
    }
    default: {
        $user_type_sort_order = 'asc';
        $user_type_sort_order_display = 'desc';
        $user_type_column_class = 'sortable';

        $username_sort_order = 'asc';
        $username_sort_order_display = 'desc';
        $username_column_class = 'sortable';

        $user_email_sort_order = 'asc';
        $user_email_sort_order_display = 'desc';
        $user_email_column_class = 'sortable';

        $ipaddress_sort_order = 'asc';
        $ipaddress_sort_order_display = 'desc';
        $ipaddress_column_class = 'sortable';

        $added_date_sort_order = 'asc';
        $added_date_sort_order_display = 'desc';
        $added_date_column_class = 'sortable';

        break;
    }
}
$search_value = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
?>
<div id="yop-main-area" class="bootstrap-yop wrap">
    <input type="hidden" name="_token" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-get-vote-details' ) ); ?>">
    <div id="icon-options-general" class="icon32"></div>
    <h1>
        <span class="glyphicon glyphicon-signal" style="margin-right:10px;"></span><?php esc_html_e( 'Poll results for', 'yop-poll' ); ?> <?php echo esc_html( $poll->name ); ?>
        <a href="<?php echo esc_url( add_query_arg(
            array(
                'page' => 'yop-polls',
                'action' => false,
                'poll_id' => false,
                '_token' => false,
                'order_by' => false,
                'sort_order' => false,
                'q' => false,
                'exportCustoms' => false,
            ) ) );?>" class="page-title-action">
            <?php esc_html_e( 'All Polls', 'yop-poll' ); ?>
        </a>
    </h1>
    <div class="container-fluid">
        <div class="row submenu" style="margin-top:30px; margin-bottom: 50px;">
            <div class="col-md-4">
                <a href="<?php echo esc_url( add_query_arg(
                    array(
                        'page' => 'yop-polls',
                        'action' => 'results',
                        'poll_id' => $poll->id,
                        '_token' => false,
                        'order_by' => false,
                        'sort_order' => false,
                        'q' => false,
                        'exportCustoms' => false
                    ) ) ); ?>" class="btn btn-link btn-block">
                    <?php esc_html_e( 'Results', 'yop-poll' ); ?>
                </a>
            </div>
            <div class="col-md-4">
                <a class="btn btn-link btn-block btn-underline">
                    <?php esc_html_e( 'View votes', 'yop-poll' ); ?>
                </a>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="get" action="" id="searchForm">
                    <p class="search-box">
                        <label class="screen-reader-text" for="post-search-input">
                            <?php esc_html_e( 'Search Votes', 'yop-poll' ); ?>:
                        </label>
                        <input type="hidden" name="page" value="yop-polls">
                        <input type="hidden" name="action" value="view-votes">
                        <input type="hidden" name="poll_id" value="<?php echo esc_attr( $poll->id ); ?>">
                        <input id="votes-search-input" name="q" value="<?php echo esc_attr( $search_value ); ?>" type="search">
                        <input id="search-submit" class="button" value="<?php esc_html_e( 'Search Votes', 'yop-poll' ); ?>" type="submit">
                    </p>
                    <button class="export-logs-button button" id="doaction" type="button" name="export"><?php esc_html_e( 'Export', 'yop-poll' ); ?></button>
                    <input type="hidden" name="doExport" id="doExport" value="">
                    <button class="add-votes-button button" type="button" name="add-votes" data-toggle="modal" data-target="#modal-add-votes-manually">
						<?php esc_html_e( 'Add Votes', 'yop-poll' ); ?>
					</button>
					<div id="modal-add-votes-manually" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<?php esc_html_e( 'Add Votes Manually', 'yop-poll' ); ?>
								</h4>
							</div>
							<div class="modal-body section-main-add-votes-manually">
								<?php
								foreach ( $poll->elements as $poll_element ) {
									switch ( $poll_element->etype ) {
										case 'text-question': {
											?>
											<div class="question-section" data-id="<?php echo esc_attr( $poll_element->id ); ?>">
												<h4 class="text-center">
													<?php echo esc_html( $poll_element->etext ); ?>
												</h4>
												<?php
												foreach ( $poll_element->answers as $answer ) {
													?>
													<div class="row answer-section">
														<div class="col-md-12">
															<h5>
																<?php
																	echo esc_html( $answer->stext );
																?>
															</h5>
														</div>
													</div>
													<div class="row">
														<div class="col-md-4">
															<div class="input-group">
																<input type="text" class="form-control answer-element" value="0" data-id="<?php echo esc_attr( $answer->id ); ?>">
																<span class="help-block">
																	<?php esc_html_e( 'Number of votes for this answer', 'yop-poll' ); ?>
																</span>
															</div>
														</div>
														<div class="col-md-8">&nbsp;</div>
													</div>
													<?php
												}
											?>
											</div>
											<?php
											break;
										}
									}
								}
								?>
							</div>
							<div class="modal-footer section-footer-add-votes-manually">
								<input type="hidden" name="_token-add-votes-manually" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-add-votes-manually' ) ); ?>">
								<button type="button" class="btn btn-default btn-cancel-add-votes-manually">
									<?php esc_html_e( 'Cancel', 'yop-poll' ); ?>
								</button>
								<button type="button" class="btn btn-primary btn-submit-add-votes-manually" data-poll-id="<?php echo esc_attr( $poll->id ); ?>">
									<?php esc_html_e( 'Add Votes', 'yop-poll' ); ?>
								</button>
								<span class="spinner hide"></span>
							</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
                </form>
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <input type="hidden" name="_bulk_token" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-bulk-votes' ) ); ?>">
                        <label for="bulk-action-selector-top" class="screen-reader-text">
                            <?php esc_html_e( 'Select bulk action', 'yop-poll' ); ?>
                        </label>
                        <select name="action" class="logs-bulk-action-top admin-select">
                            <option value="-1" class="hide-if-no-js">
                                <?php esc_html_e( 'Bulk Actions', 'yop-poll' ); ?>
                            </option>
                            <option value="trash" class="hide-if-no-js">
                                <?php esc_html_e( 'Move to Trash', 'yop-poll' ); ?>
                            </option>
                        </select>
                        <input class="button votes-bulk-action" data-position="top" value="<?php esc_html_e( 'Apply', 'yop-poll' ); ?>" type="submit">
                    </div>
                    <h2 class="screen-reader-text">
                        <?php esc_html_e( 'Votes list navigation', 'yop-poll' ); ?>
                    </h2>
                    <div class="tablenav-pages">
						<span class="displaying-num">
							<?php echo sprintf( _n( '%s item', '%s items', count( $votes ), 'yop-poll' ), count( $votes ) ); ?>
						</span>
                        <?php
                        if ( 1 < $total_pages ) {
                            ?>
                            <span class="pagination-links">
							<?php echo wp_kses_post( $pagination['first_page'] ); ?>
                                <?php echo wp_kses_post( $pagination['previous_page'] ); ?>
								<span class="paging-input">
								<label for="current-page-selector" class="screen-reader-text">
									<? esc_html_e( 'Current Page', 'yop-poll' ); ?>
								</label>
								<input class="current-page"
                                       id="current-poll-page-selector"
                                       type="text"
                                       name="page_no"
                                       value="<?php echo esc_attr( $params['page_no'] ); ?>"
                                       size="1"
                                       aria-describedby="table-paging">
								<span class="tablenav-paging-text"> of
									<span class="total-pages">
										<?php echo esc_html( $total_pages ); ?>
									</span>
								</span>
							</span>
								<?php echo wp_kses_post( $pagination['next_page'] ); ?>
                                <?php echo wp_kses_post( $pagination['last_page'] ); ?>
						</span>
                            <?php
                        }
                        ?>
                    </div>
                    <br class="clear">
                </div>
                <table class="wp-list-table yop-table widefat striped pages ">
                    <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column">
                            <label class="screen-reader-text" for="cb-select-all-1">
                                <?php esc_html_e( 'Select All', 'yop-poll' ); ?>
                            </label>
                            <input id="cb-select-all-1" type="checkbox">
                        </td>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $user_type_column_class ) . ' ' . esc_attr( $user_type_sort_order_display ); ?>">
                            <a href="
								<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'user_type',
                                        'sort_order' => $user_type_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
								">
									<span>
										<?php esc_html_e( 'User Type', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $username_column_class ) . ' ' . esc_attr( $username_sort_order_display ); ?>">
                            <a href="
									<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'username',
                                        'sort_order' => $username_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
									">
									<span>
										<?php esc_html_e( 'Username', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $user_email_column_class ) . ' ' . esc_attr( $user_email_sort_order_display ); ?>">
                            <a href="
								<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'user_email',
                                        'sort_order' => $user_email_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
								">
									<span>
										<?php esc_html_e( 'Email', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $ipaddress_column_class ) . ' ' . esc_attr( $ipaddress_sort_order_display ); ?>">
                            <a href="
								<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'ipaddress',
                                        'sort_order' => $ipaddress_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
								">
									<span>
										<?php esc_html_e( 'Ipaddress', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $added_date_column_class ) . ' ' . esc_attr( $added_date_sort_order_display ); ?>">
                            <a href="
								<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'added_date',
                                        'sort_order' => $added_date_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
								">
									<span>
										<?php esc_html_e( 'Date', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                    </tr>
                    </thead>
                    <?php
                    foreach ( $votes as $vote ) {
                        ?>
                        <tr class="iedit author-self level-0 post-17 type-post status-publish format-standard hentry category-uncategorized">
                            <th scope="row" class="check-column">
                                <label class="screen-reader-text" for="cb-select-17">
                                    <?php esc_html_e( 'Select', 'yop-poll' ); ?>
                                </label>
                                <input name="votes[]" value="<?php echo esc_html( $vote['id'] ); ?>" type="checkbox">
                                <div class="locked-indicator"></div>
                            </th>
                            <td class="title column-title has-row-actions column-primary page-title" data-colname="User Type">
                                <?php echo esc_html( $vote['user_type'] ); ?>
                                <div class="row-actions">
									<span class="view">
										<a href="#" class="details-operation" data-vote-id="<?php echo esc_attr( $vote['id'] ); ?>" data-ajax-sent="no" title="<?php esc_html_e( 'View details for this record', 'yop-poll' ); ?>">
											<?php esc_html_e( 'View Details', 'yop-poll' ); ?> |
										</a>
									</span>
                                    <span class="trash">
										<a class="delete-vote" title="<?php esc_html_e( 'Move this log record to the Trash', 'yop-poll' ); ?>"
                                           href="#" data-id="<?php echo esc_attr( $vote['id'] ); ?>"><?php esc_html_e( 'Trash', 'yop-poll' ); ?></a>
									</span>
                                </div>
                                <div id="vote-details-div-<?php echo esc_attr( $vote['id'] ); ?>" style="display: none; padding-top: 10px;">
                                </div>
                            </td>
                            <td class="title column-title has-row-actions column-primary page-title" data-colname="Username">
                                <?php
                                echo esc_html( $vote['username'] );
                                ?>
                            </td>
                            <td class="title column-title has-row-actions column-primary page-title" data-colname="Email">
                                <?php echo esc_html( $vote['user_email'] ); ?>
                            </td>
                            <td class="title column-title has-row-actions column-primary page-title" data-colname="Ipaddress">
                                <?php echo esc_html( $vote['ipaddress'] ); ?>
                            </td>
                            <td class="title column-title has-row-actions column-primary page-title" data-colname="Added Date">
                                <?php echo esc_html( date( $date_format . ' @ ' . $time_format, strtotime( $vote['added_date'] ) ) ); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column">
                            <label class="screen-reader-text" for="cb-select-all-1">
                                <?php esc_html_e( 'Select All', 'yop-poll' ); ?>
                            </label>
                            <input id="cb-select-all-1" type="checkbox">
                        </td>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $user_type_column_class ) . ' ' . esc_attr( $user_type_sort_order_display ); ?>">
                            <a href="
								<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'user_type',
                                        'sort_order' => $user_type_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
								">
									<span>
										<?php esc_html_e( 'User Type', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $username_column_class ) . ' ' . esc_attr( $username_sort_order_display ); ?>">
                            <a href="
									<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'username',
                                        'sort_order' => $username_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
									">
									<span>
										<?php esc_html_e( 'Username', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $user_email_column_class ) . ' ' . esc_attr( $user_email_sort_order_display ); ?>">
                            <a href="
								<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'user_email',
                                        'sort_order' => $user_email_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
								">
									<span>
										<?php esc_html_e( 'Email', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $ipaddress_column_class ) . ' ' . esc_attr( $ipaddress_sort_order_display ); ?>">
                            <a href="
								<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'ipaddress',
                                        'sort_order' => $ipaddress_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
								">
									<span>
										<?php esc_html_e( 'Ipaddress', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $added_date_column_class ) . ' ' . esc_attr( $added_date_sort_order_display ); ?>">
                            <a href="
								<?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => 'view-votes',
                                        'poll_id' => $poll->id,
                                        '_token' => false,
                                        'order_by' => 'added_date',
                                        'sort_order' => $added_date_sort_order,
                                        'q' => sanitize_text_field( $params['q'] ),
                                        'page_no' => sanitize_text_field( $params['page_no'] )
                                    )
                                )
                            );
                            ?>
								">
									<span>
										<?php esc_html_e( 'Date', 'yop-poll' ); ?>
									</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                    </tr>
                    </thead>
                </table>
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-bottom" class="screen-reader-text">
                            <?php esc_html_e( 'Select bulk action', 'yop-poll' ); ?>
                        </label>
                        <select name="action" class="logs-bulk-action-bottom admin-select">
                            <option value="-1" class="hide-if-no-js">
                                <?php esc_html_e( 'Bulk Actions', 'yop-poll' ); ?>
                            </option>
                            <option value="trash" class="hide-if-no-js">
                                <?php esc_html_e( 'Move to Trash', 'yop-poll' ); ?>
                            </option>
                        </select>
                        <input class="button votes-bulk-action" data-position="bottom" value="<?php esc_html_e( 'Apply', 'yop-poll' ); ?>" type="submit">
                    </div>
                    <h2 class="screen-reader-text">
                        <?php esc_html_e( 'Pages list navigation', 'yop-poll' ); ?>
                    </h2>
                    <div class="tablenav-pages">
						<span class="displaying-num">
							<?php echo sprintf( _n( '%s item', '%s items', count( $votes ), 'yop-poll' ), count( $votes ) ); ?>
						</span>
                        <?php
                        if ( 1 < $total_pages ) {
                            ?>
                            <span class="pagination-links">
							<?php echo wp_kses_post( $pagination['first_page'] ); ?>
                                <?php echo wp_kses_post( $pagination['previous_page'] ); ?>
								<span class="paging-input">
								<label for="current-page-selector" class="screen-reader-text">
									<? esc_html_e( 'Current Page', 'yop-poll' ); ?>
								</label>
								<span class="tablenav-paging-text">
									<?php echo esc_html( $params['page_no'] ); ?> of
									<span class="total-pages">
										<?php echo esc_html( $total_pages ); ?>
									</span>
								</span>
							</span>
								<?php echo wp_kses_post( $pagination['next_page'] ); ?>
                                <?php echo wp_kses_post( $pagination['last_page'] ); ?>
						</span>
                            <?php
                        }
                        ?>
                    </div>
                    <br class="clear">
                </div>
            </div>
        </div>
    </div>
</div>
