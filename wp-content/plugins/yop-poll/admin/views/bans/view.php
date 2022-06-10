<?php
switch ( $params['order_by'] ) {
    case 'name':{
        $poll_name_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $poll_name_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $poll_name_column_class = 'sorted';

        $author_sort_order = 'asc';
        $author_sort_order_display = 'desc';
        $author_column_class = 'sortable';

        $ban_by_sort_order = 'asc';
        $ban_by_sort_order_display = 'desc';
        $ban_by_column_class = 'sortable';

        $ban_value_sort_order = 'asc';
        $ban_value_sort_order_display = 'desc';
        $ban_value_column_class = 'sortable';

        $ban_date_sort_order = 'asc';
        $ban_date_sort_order_display = 'desc';
        $ban_date_column_class = 'sortable';
        break;
    }
    case 'author':{
        $poll_name_sort_order = 'asc';
        $poll_name_sort_order_display = 'desc';
        $poll_name_column_class = 'sortable';

        $author_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $author_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $author_column_class = 'sorted';

        $ban_by_sort_order = 'asc';
        $ban_by_sort_order_display = 'desc';
        $ban_by_column_class = 'sortable';

        $ban_value_sort_order = 'asc';
        $ban_value_sort_order_display = 'desc';
        $ban_value_column_class = 'sortable';

        $ban_date_sort_order = 'asc';
        $ban_date_sort_order_display = 'desc';
        $ban_date_column_class = 'sortable';
        break;
    }
	default: {
		$poll_name_sort_order = 'asc';
		$poll_name_sort_order_display = 'desc';
		$poll_name_column_class = 'sortable';

		$author_sort_order = 'asc';
		$author_sort_order_display = 'desc';
		$author_column_class = 'sortable';

		$ban_by_sort_order = 'asc';
		$ban_by_sort_order_display = 'desc';
		$ban_by_column_class = 'sortable';

		$ban_value_sort_order = 'asc';
		$ban_value_sort_order_display = 'desc';
		$ban_value_column_class = 'sortable';

		$ban_date_sort_order = 'asc';
		$ban_date_sort_order_display = 'desc';
		$ban_date_column_class = 'sortable';
		break;
	}
	case 'ban_by':{
        $poll_name_sort_order = 'asc';
        $poll_name_sort_order_display = 'desc';
        $poll_name_column_class = 'sortable';

        $author_sort_order = 'asc';
        $author_sort_order_display = 'desc';
        $author_column_class = 'sortable';

        $ban_by_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $ban_by_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $ban_by_column_class = 'sorted';

        $ban_value_sort_order = 'asc';
        $ban_value_sort_order_display = 'desc';
        $ban_value_column_class = 'sortable';

        $ban_date_sort_order = 'asc';
        $ban_date_sort_order_display = 'desc';
        $ban_date_column_class = 'sortable';
        break;
    }
	case 'ban_value':{
        $poll_name_sort_order = 'asc';
        $poll_name_sort_order_display = 'desc';
        $poll_name_column_class = 'sortable';

        $author_sort_order = 'asc';
        $author_sort_order_display = 'desc';
        $author_column_class = 'sortable';

        $ban_by_sort_order = 'asc';
        $ban_by_sort_order_display = 'desc';
        $ban_by_column_class = 'sortable';

        $ban_value_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $ban_value_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $ban_value_column_class = 'sorted';

        $ban_date_sort_order = 'asc';
        $ban_date_sort_order_display = 'desc';
        $ban_date_column_class = 'sortable';
        break;
    }
	case 'ban_date':{
        $poll_name_sort_order = 'asc';
        $poll_name_sort_order_display = 'desc';
        $poll_name_column_class = 'sortable';

        $author_sort_order = 'asc';
        $author_sort_order_display = 'desc';
        $author_column_class = 'sortable';

        $ban_by_sort_order = 'asc';
        $ban_by_sort_order_display = 'desc';
        $ban_by_column_class = 'sortable';

        $ban_value_sort_order = 'asc';
        $ban_value_sort_order_display = 'asc';
        $ban_value_column_class = 'sortable';

        $ban_date_sort_order = ( isset( $params['sort_order'] ) && ( 'asc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $ban_date_sort_order_display = ( isset( $params['sort_order'] ) && ( 'desc' === $params['sort_order'] ) ) ? 'desc' : 'asc';
        $ban_date_column_class = 'sorted';
        break;
    }
}
?>
<div id="yop-main-area" class="bootstrap-yop wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h1>
		<?php esc_html_e( 'Bans', 'yop-poll' ); ?>
		<a href="<?php echo esc_url(
            add_query_arg(
            array(
                'page' => 'yop-poll-bans',
                'action' => 'add',
                'ban_id' => false,
                '_token' => false,
                'order_by' => false,
                'sort_order' => false,
                'q' => false
             ) ) ); ?>" class="page-title-action">
			<?php esc_html_e( 'Add New', 'yop-poll' ); ?>
		</a>
	</h1>
	<form method="get">
		<input type="hidden" name="_token" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-view-bans' ) ); ?>">
	    <p class="search-box">
			<label class="screen-reader-text" for="post-search-input">
				<?php esc_html_e( 'Search Bans', 'yop-poll' ); ?>:
			</label>
            <input type="hidden" name="page" value="yop-poll-bans">
			<input id="ban-search-input" name="q" value="" type="search">
			<input id="search-submit" class="button" value="<?php esc_html_e( 'Search Bans', 'yop-poll' ); ?>" type="submit">
	    </p>
	</form>
    <div class="tablenav top">
		<div class="alignleft actions bulkactions">
            <input type="hidden" name="_bulk_token" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-bulk-bans' ) ); ?>">
			<label for="bulk-action-selector-top" class="screen-reader-text">
				<?php esc_html_e( 'Select bulk action', 'yop-poll' ); ?>
			</label>
			<select name="action" class="bans-bulk-action-top admin-select">
				<option value="-1" class="hide-if-no-js">
					<?php esc_html_e( 'Bulk Actions', 'yop-poll' ); ?>
				</option>
				<option value="trash" class="hide-if-no-js">
					<?php esc_html_e( 'Move to Trash', 'yop-poll' ); ?>
				</option>
			</select>
			<input class="button bans-bulk-action" data-position="top" value="<?php esc_html_e( 'Apply', 'yop-poll' ); ?>" type="submit">
		</div>
        <h2 class="screen-reader-text">
            <?php esc_html_e( 'Bans list navigation', 'yop-poll' ); ?>
        </h2>
		<div class="tablenav-pages">
			<span class="displaying-num">
				<?php echo sprintf( _n( '%s item', '%s items', count( $bans ), 'yop-poll' ), count( $bans ) ); ?>
			</span>
            <?php
            if ( 1 < $total_pages ) {
            ?>
            <span class="pagination-links">
                <?php echo esc_html( $pagination['first_page'] ); ?>
                <?php echo esc_html( $pagination['previous_page'] ); ?>
                <span class="paging-input">
                    <label for="current-page-selector" class="screen-reader-text">
                        <? esc_html_e( 'Current Page', 'yop-poll' );?>
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
				<th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $poll_name_column_class ) . ' ' . esc_attr( $poll_name_sort_order_display ); ?>">
					<a href="
                        <?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => false,
                                        'poll_id' => false,
                                        '_token' => false,
                                        'order_by' => 'name',
                                        'sort_order' => $poll_name_sort_order,
                                        'q' => htmlentities( $params['q'] ),
                                        'page_no' => $params['page_no']
                                    )
                                )
                            );
                            ?>
                        ">
						<span>
							<?php esc_html_e( 'Poll', 'yop-poll' ); ?>
						</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
                <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $author_column_class ) . ' ' . esc_attr( $author_sort_order_display ); ?>">
					<a href="
                        <?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => false,
                                        'poll_id' => false,
                                        '_token' => false,
                                        'order_by' => 'author',
                                        'sort_order' => $author_sort_order,
                                        'q' => htmlentities( $params['q'] ),
                                        'page_no' => $params['page_no']
                                    )
                                )
                            );
                            ?>
                        ">
						<span>
							<?php esc_html_e( 'Author', 'yop-poll' ); ?>
						</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $ban_by_column_class ) . ' ' . esc_attr( $ban_by_sort_order_display ); ?>">
                    <a href="
                    <?php echo esc_url(
                            add_query_arg(
                                array(
                                    'action' => false,
                                    'poll_id' => false,
                                    '_token' => false,
                                    'order_by' => 'ban_by',
                                    'sort_order' => $ban_by_sort_order,
                                    'q' => htmlentities( $params['q'] ),
                                    'page_no' => $params['page_no']
                                )
                            )
                        );
                    ?>
                    ">
						<span>
							<?php esc_html_e( 'Ban By', 'yop-poll' ); ?>
						</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $ban_value_column_class ) . ' ' . esc_attr( $ban_value_sort_order_display ); ?>">
					<a href="
                    <?php echo esc_url(
                            add_query_arg(
                                array(
                                    'action' => false,
                                    'poll_id' => false,
                                    '_token' => false,
                                    'order_by' => 'ban_value',
                                    'sort_order' => $ban_value_sort_order,
                                    'q' => htmlentities( $params['q'] ),
                                    'page_no' => $params['page_no']
                                )
                            )
                        );
                    ?>
                    ">
						<span>
							<?php esc_html_e( 'Value', 'yop-poll' ); ?>
						</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
                <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $ban_date_column_class ) . ' ' . esc_attr( $ban_date_sort_order_display ); ?>">
					<a href="
                    <?php echo esc_url(
                            add_query_arg(
                                array(
                                    'action' => false,
                                    'poll_id' => false,
                                    '_token' => false,
                                    'order_by' => 'ban_date',
                                    'sort_order' => $ban_date_sort_order,
                                    'q' => htmlentities( $params['q'] ),
                                    'page_no' => $params['page_no']
                                )
                            )
                        );
                    ?>
                    ">
						<span>
							<?php esc_html_e( 'Added On', 'yop-poll' ); ?>
						</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			</tr>
		</thead>
		<?php
		foreach ( $bans as $ban ) {
		?>
        <tr class="iedit author-self level-0 post-17 type-post status-publish format-standard hentry category-uncategorized">
			<th scope="row" class="check-column">
				<label class="screen-reader-text" for="cb-select-17">
					<?php esc_html_e( 'Select', 'yop-poll' ); ?>
				</label>
				<input name="bans[]" value="<?php echo esc_attr( $ban['id'] ); ?>" type="checkbox">
				<div class="locked-indicator"></div>
			</th>
			<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
				<strong>
					<a class="row-title" href="<?php echo esc_url( add_query_arg(
                        array(
                            'page' => 'yop-poll-bans',
                            'action' => 'edit',
                            'ban_id' => $ban['id'],
                            '_token' => false,
                            'order_by' => false,
                            'sort_order' => false,
                            'q' => false
                        ) ) );?>"
                        title="<?php esc_html_e( 'Poll name', 'yop-poll' ); ?>">
						<?php echo esc_html( ( 0 !== intval( $ban['poll_id'] ) ) ? $ban['name'] : 'All Polls' ); ?>
					</a>
				</strong>
				<div class="row-actions">
					<span class="edit">
						<a href="<?php echo esc_url( add_query_arg(
                            array(
                                'page' => 'yop-poll-bans',
                                'action' => 'edit',
                                'ban_id' => $ban['id'],
                                '_token' => false,
                                'order_by' => false,
                                'sort_order' => false,
                                'q' => false
                            ) ) );?>" title="<?php esc_html_e( 'Edit this ban', 'yop-poll' ); ?>">
							<?php esc_html_e( 'Edit', 'yop-poll' ); ?>
						</a> |
					</span>
					<span class="trash">
						<a class="delete-ban" title="<?php esc_html_e( 'Move this ban to the Trash', 'yop-poll' ); ?>"
                            href="#"
                            data-id="<?php echo esc_attr( $ban['id'] ); ?>">
							<?php esc_html_e( 'Trash', 'yop-poll' ); ?>
						</a>
					</span>
				</div>
			</td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
				<?php echo esc_html( $ban['author'] ); ?>
			</td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
				<?php echo esc_html( $ban['b_by'] ); ?>
			</td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
				<?php echo esc_html( $ban['b_value'] ); ?>
			</td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
				<?php echo esc_html( date( $date_format . ' @ ' . $time_format, strtotime( $ban['added_date'] ) ) ); ?>
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
                <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $poll_name_column_class ) . ' ' . esc_attr( $poll_name_sort_order_display ); ?>">
					<a href="
                        <?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => false,
                                        'poll_id' => false,
                                        '_token' => false,
                                        'order_by' => 'name',
                                        'sort_order' => $poll_name_sort_order,
                                        'q' => htmlentities( $params['q'] ),
                                        'page_no' => $params['page_no']
                                    )
                                )
                            );
                            ?>
                        ">
						<span>
							<?php esc_html_e( 'Poll', 'yop-poll' ); ?>
						</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
                <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $author_column_class ) . ' ' . esc_attr( $author_sort_order_display ); ?>">
					<a href="
                        <?php echo esc_url(
                                add_query_arg(
                                    array(
                                        'action' => false,
                                        'poll_id' => false,
                                        '_token' => false,
                                        'order_by' => 'author',
                                        'sort_order' => $author_sort_order,
                                        'q' => htmlentities( $params['q'] ),
                                        'page_no' => $params['page_no']
                                    )
                                )
                            );
                            ?>
                        ">
						<span>
							<?php esc_html_e( 'Author', 'yop-poll' ); ?>
						</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $ban_by_column_class ) . ' ' . esc_attr( $ban_by_sort_order_display ); ?>">
                    <a href="
                    <?php echo esc_url(
                            add_query_arg(
                                array(
                                    'action' => false,
                                    'poll_id' => false,
                                    '_token' => false,
                                    'order_by' => 'ban_by',
                                    'sort_order' => $ban_by_sort_order,
                                    'q' => htmlentities( $params['q'] ),
                                    'page_no' => $params['page_no']
                                )
                            )
                        );
                    ?>
                    ">
						<span>
							<?php esc_html_e( 'Ban By', 'yop-poll' ); ?>
						</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
				<th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $ban_value_column_class ) . ' ' . esc_attr( $ban_value_sort_order_display ); ?>">
					<a href="
                    <?php echo esc_url(
                            add_query_arg(
                                array(
                                    'action' => false,
                                    'poll_id' => false,
                                    '_token' => false,
                                    'order_by' => 'ban_value',
                                    'sort_order' => $ban_value_sort_order,
                                    'q' => htmlentities( $params['q'] ),
                                    'page_no' => $params['page_no']
                                )
                            )
                        );
                    ?>
                    ">
						<span>
							<?php esc_html_e( 'Value', 'yop-poll' ); ?>
						</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
                <th scope="col" class="manage-column column-title column-primary <?php echo esc_attr( $ban_date_column_class ) . ' ' . esc_attr( $ban_date_sort_order_display ); ?>">
					<a href="
                    <?php echo esc_url(
                            add_query_arg(
                                array(
                                    'action' => false,
                                    'poll_id' => false,
                                    '_token' => false,
                                    'order_by' => 'ban_date',
                                    'sort_order' => $ban_date_sort_order,
                                    'q' => htmlentities( $params['q'] ),
                                    'page_no' => $params['page_no']
                                )
                            )
                        );
                    ?>
                    ">
						<span>
							<?php esc_html_e( 'Added On', 'yop-poll' ); ?>
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
			<select name="action" class="bans-bulk-action-bottom admin-select">
                <option value="-1" class="hide-if-no-js">
					<?php esc_html_e( 'Bulk Actions', 'yop-poll' ); ?>
				</option>
				<option value="trash" class="hide-if-no-js">
					<?php esc_html_e( 'Move to Trash', 'yop-poll' ); ?>
				</option>
			</select>
			<input class="button bans-bulk-action" data-position="bottom" value="<?php esc_html_e( 'Apply', 'yop-poll' ); ?>" type="submit">
		</div>
        <!--
		<div class="alignleft actions">
			<label for="filter-by-date" class="screen-reader-text">
				<?php esc_html_e( 'Filter by date', 'yop-poll' ); ?>
			</label>
			<select name="m" id="filter-by-date">
				<option selected="selected" value="0">
					<?php esc_html_e( 'View All Polls', 'yop-poll' ); ?>
				</option>
			</select>
			<input name="filter_action" id="post-query-submit" class="button" value="<?php esc_html_e( 'Filter', 'yop-poll' ); ?>" type="submit">
		</div>
        -->
        <h2 class="screen-reader-text">
            <?php esc_html_e( 'Pages list navigation', 'yop-poll' ); ?>
        </h2>
		<div class="tablenav-pages">
			<span class="displaying-num">
				<?php echo sprintf( _n( '%s item', '%s items', $total_bans, 'yop-poll' ), $total_bans ); ?>
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
