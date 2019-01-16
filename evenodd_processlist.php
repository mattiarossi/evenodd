<?php
/*
 All Emoncms code is released under the GNU Affero General Public License.
 See COPYRIGHT.txt and LICENSE.txt.
 ---------------------------------------------------------------------
 Emoncms - open source energy visualisation
 Part of the OpenEnergyMonitor project: http://openenergymonitor.org
 */

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');


// Evenodd Processlist Module
class Evenodd_ProcessList
{
    private $log;
    private $parentProcessModel;


    // Module required constructor, receives parent as reference
    public function __construct(&$parent)
    {
        $this->log = new EmonLogger(__FILE__);
        $this->parentProcessModel = &$parent;

    }

    // Module required process configuration, $list array index position is not used, function name is used instead
    public function process_list()
    {
        // Note on engine selection

        // The engines listed against each process must be the supported engines for each process - and are only used in the input and node config GUI dropdown selectors
        // By using the create feed api and input set processlist its possible to create any feed type with any process list combination.
        // Only feeds capable of using a particular processor are displayed to the user and can be selected from the gui.
        // Daily datatype automaticaly adjust feed interval to 1d and user cant change it from gui.
        // If there is only one engine available for a processor, it is selected and user cant change it from gui.
        // The default selected engine is the first in the array of the supported engines for each processor.
        // Virtual feeds are feeds that are calculed in realtime when queried and use a processlist as post processor.
        // Processors that write or update a feed are not supported and hidden from the gui on the context of virtual feeds.

        // 0=>Name | 1=>Arg type | 2=>function | 3=>No. of datafields if creating feed | 4=>Datatype | 5=>Group | 6=>Engines | 'desc'=>Description | 'requireredis'=>true | 'nochange'=>true  | 'helpurl'=>"http://..."
        $list[] = array("If ODD", ProcessArg::NONE, "if_odd", 0, DataType::UNDEFINED, "Conditional",'nochange'=>true, 'desc'=>"");
        $list[] = array("If EVEN", ProcessArg::NONE, "if_even", 0, DataType::UNDEFINED, "Conditional",'nochange'=>true, 'desc'=>"");
        return $list;
    }


    // \/ Below are functions of this module processlist, same name must exist on process_list()

    public function if_odd($noarg, $time, $value, $options) {
        if ($value % 2 == 0){
        $this->parentProcessModel->proc_skip_next = true;
    }

        return $value;
    }
    public function if_even($noarg, $time, $value) {
        if ($value % 2 == 1)
        $this->parentProcessModel->proc_skip_next = true;
        return $value;
    }
}