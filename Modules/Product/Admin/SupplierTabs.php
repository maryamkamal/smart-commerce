<?php

namespace Modules\Product\Admin;

use Modules\Admin\Ui\Tab;
use Modules\Admin\Ui\Tabs;

class SupplierTabs extends Tabs
{
    public function make()
    {
        $this->group('supplier_information', trans('product::suppliers.tabs.group.supplier_information'))
            ->active()
            ->add($this->general());
            // ->add($this->seo());
    }

    private function general()
    {
        return tap(new Tab('general', trans('product::suppliers.tabs.general')), function (Tab $tab) {
            $tab->active();
            $tab->weight(5);
            $tab->fields(['suppliers_name']);
            $tab->view('product::admin.suppliers.tabs.general');
        });
    }

    // private function images()
    // {
    //     if (! auth()->user()->hasAccess('admin.media.index')) {
    //         return;
    //     }

    //     return tap(new Tab('images', trans('brand::brands.tabs.images')), function (Tab $tab) {
    //         $tab->weight(10);
    //         $tab->view('brand::admin.brands.tabs.images');
    //     });
    // }

    // private function seo()
    // {
    //     return tap(new Tab('seo', trans('supplier::suppliers.tabs.seo')), function (Tab $tab) {
    //         $tab->weight(15);
    //         $tab->fields(['slug']);
    //         $tab->view('supplier::admin.suppliers.tabs.seo');
    //     });
    // }
}
