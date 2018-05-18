<?php
// lib/php/themes/bootstrap/vhosts.php 20170101 - 20180512
// Copyright (C) 2015-2018 Mark Constable <markc@renta.net> (AGPL-3.0)

class Themes_Bootstrap_Vhosts extends Themes_Bootstrap_Theme
{
    public function create(array $in) : string
    {
error_log(__METHOD__);

        return $this->editor($in);
    }

    public function update(array $in) : string
    {
error_log(__METHOD__);

        return $this->editor($in);
    }

    public function list(array $in) : string
    {
error_log(__METHOD__);

        // TODO migrate plans to a database table
        $plans = [
            ['Select Plan', ''],
            ['Personal - 1 GB Storage, 1 Domain, 1 Website, 1 Mailbox', 'personal'],
            ['SOHO - 5 GB Storage, 2 Domains, 2 Websites, 5 Mailboxes', 'soho'],
            ['Business - 10 GB Storage, 5 Domains, 5 Websites, 10 Mailboxes', 'business'],
            ['Enterprise - 20 GB Storage, 10 Domains, 10 Websites, 20 Mailboxes', 'enterprise'],
        ];

        $plans_buf = $this->dropdown($plans, 'plan', '', '', 'custom-select');

        $createmodal = $this->modal([
            'id'      => 'createmodal',
            'title'   => 'Create New Vhost',
            'action'  => 'create',
            'footer'  => 'Create',
            'body'    => '
                  <div class="form-group">
                    <label for="domain" class="form-control-label">Vhost</label>
                    <input type="text" class="form-control" id="domain" name="domain">
                  </div>
                  <div class="form-group">
                    <label for="plan" class="form-control-label">Plan</label>' . $plans_buf . '
                  </div>',
        ]);

        return '
        <div class="col-12">
          <h3>
            <i class="fa fa-globe fa-fw"></i> Vhosts
            <a href="#" title="Add new vhost" data-toggle="modal" data-target="#createmodal">
              <small><i class="fas fa-plus-circle fa-fw"></i></small>
            </a>
          </h3>
        </div>
      </div><!-- END UPPER ROW -->
      <div class="row">
        <div class="table-responsive">
          <table id=vhosts class="table table-sm" style="min-width:1100px;table-layout:fixed">
            <thead class="nowrap">
              <tr>
                <th>Domain</th>
                <th>Alias&nbsp;</th>
                <th></th>
                <th></th>
                <th>Mbox&nbsp;</th>
                <th></th>
                <th></th>
                <th>Mail&nbsp;</th>
                <th></th>
                <th></th>
                <th>Disk&nbsp;</th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tfoot>
            </tfoot>
          </table>
        </div>' . $createmodal . '
        <script>
$(document).ready(function() {
  $("#vhosts").DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": "?x=json&o=vhosts&m=list",
    "order": [[ 15, "desc" ]],
    "columnDefs": [
      {"targets":0,   "className":"text-truncate", "width":"25%"},
      {"targets":1,   "className":"text-right", "width":"3rem"},
      {"targets":2,   "className":"text-center", "width":"0.5rem", "sortable": false},
      {"targets":3,   "width":"3rem"},
      {"targets":4,   "className":"text-right", "width":"3rem"},
      {"targets":5,   "className":"text-center", "width":"0.5rem", "sortable": false},
      {"targets":6,   "width":"3rem"},
      {"targets":7,   "className":"text-right", "width":"4rem"},
      {"targets":8,   "className":"text-center", "width":"0.5rem", "sortable": false},
      {"targets":9,   "width":"4rem"},
      {"targets":10,  "className":"text-right", "width":"4rem"},
      {"targets":11,  "className":"text-center", "width":"0.5rem", "sortable": false},
      {"targets":12,  "width":"4rem"},
      {"targets":13,  "className":"text-right", "width":"4rem", "sortable": false},
      {"targets":14,  "visible":false, "sortable": true},
      {"targets":15,  "visible":false, "sortable": true},
    ]
  });
});
        </script>';
    }

    private function editor(array $in) : string
    {
error_log(__METHOD__);

        extract($in);

        $active = $active ? 1 : 0;
        $header = $this->g->in['m'] === 'create' ? 'Add Vhost' : $domain;
        $submit = $this->g->in['m'] === 'create' ? '
                <a class="btn btn-secondary" href="?o=vhosts&m=list">&laquo; Back</a>
                <button type="submit" name="m" value="create" class="btn btn-primary">Add</button>' : '
                <a class="btn btn-secondary" href="?o=vhosts&m=list">&laquo; Back</a>
                <a class="btn btn-danger" href="?o=vhosts&m=delete&i=' . $this->g->in['i'] . '" title="Remove Vhost" onClick="javascript: return confirm(\'Are you sure you want to remove ' . $domain . '?\')">Remove</a>
                <button type="submit" name="m" value="update" class="btn btn-primary">Update</button>';
        $enable = $this->g->in['m'] === 'create' ? '
                <input type="text" autocorrect="off" autocapitalize="none" class="form-control" name="domain" id="domain" value="' . $domain . '">' : '
                <input type="text" class="form-control" value="' . $domain . '" disabled>
                <input type="hidden" name="domain" id="domain" value="' . $domain . '">';

        $checked = $active ? ' checked' : '';

        return '
          <div class="col-12">
            <h3><a href="?o=vhosts&m=list">&laquo;</a> ' . $header . '</h3>
          </div>
        </div><!-- END UPPER ROW -->
        <div class="row">
          <div class="col-12">
            <form method="post" action="' . $this->g->cfg['self'] . '">
              <input type="hidden" name="c" value="' . $_SESSION['c'] . '">
              <input type="hidden" name="o" value="' . $this->g->in['o'] . '">
              <input type="hidden" name="i" value="' . $this->g->in['i'] . '">
              <div class="row">
                <div class="form-group col-12 col-md-6 col-lg-4">
                  <label for="domain">Domain</label>' . $enable . '
                </div>
                <div class="form-group col-6 col-md-3 col-lg-2">
                  <label for="aliases">Max Aliases</label>
                  <input type="number" class="form-control" name="aliases" id="aliases" value="' . $aliases . '">
                </div>
                <div class="form-group col-6 col-md-3 col-lg-2">
                  <label for="mailboxes">Max Mailboxes</label>
                  <input type="number" class="form-control" name="mailboxes" id="mailboxes" value="' . $mailboxes . '">
                </div>
                <div class="form-group col-6 col-md-3 col-lg-2">
                  <label for="mailquota">Mail Quota (MB)</label>
                  <input type="number" class="form-control" name="mailquota" id="mailquota" value="' . intval($mailquota / 1000000) . '">
                </div>
                <div class="form-group col-6 col-md-3 col-lg-2">
                  <label for="diskquota">Disk Quota (MB)</label>
                  <input type="number" class="form-control" name="diskquota" id="diskquota" value="' . intval($diskquota / 1000000) . '">
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" name="active" id="active"' . $checked . '>
                      <label class="custom-control-label" for="active">Active</label>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-6 text-right">
                  <div class="btn-group">' . $submit . '
                  </div>
                </div>
              </div>
            </form>
          </div>';
    }
}

?>
