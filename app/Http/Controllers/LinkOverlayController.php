<?php

namespace App\Http\Controllers;

use App\Actions\Overlay\CrupdateLinkOverlay;
use App\Actions\Overlay\DeleteLinkOverlays;
use App\Http\Requests\CrupdateLinkOverlayRequest;
use App\LinkOverlay;
use Common\Core\BaseController;
use Common\Database\Datasource\MysqlDataSource;
use Illuminate\Http\Request;

class LinkOverlayController extends BaseController
{
    /**
     * @var LinkOverlay
     */
    private $linkOverlay;

    /**
     * @var Request
     */
    private $request;

    public function __construct(LinkOverlay $linkOverlay, Request $request)
    {
        $this->linkOverlay = $linkOverlay;
        $this->request = $request;
    }

    public function index()
    {
        $params = $this->request->all();
        $this->authorize('index', LinkOverlay::class);

        $datasource = new MysqlDataSource($this->linkOverlay, $params);

        $pagination = $datasource->paginate();

        return $this->success(['pagination' => $pagination]);
    }

    public function show(LinkOverlay $linkOverlay)
    {
        $this->authorize('show', $linkOverlay);

        return $this->success(['linkOverlay' => $linkOverlay]);
    }

    public function store(CrupdateLinkOverlayRequest $request)
    {
        $this->authorize('store', LinkOverlay::class);

        $linkOverlay = app(CrupdateLinkOverlay::class)->execute(
            $request->all(),
        );

        return $this->success(['linkOverlay' => $linkOverlay]);
    }

    public function update(
        CrupdateLinkOverlayRequest $request,
        LinkOverlay $linkOverlay
    ) {
        $this->authorize('store', $linkOverlay);

        $linkOverlay = app(CrupdateLinkOverlay::class)->execute(
            $request->all(),
            $linkOverlay,
        );

        return $this->success(['linkOverlay' => $linkOverlay]);
    }

    public function destroy(string $ids)
    {
        $overlayIds = explode(',', $ids);
        $this->authorize('destroy', [LinkOverlay::class, $overlayIds]);

        app(DeleteLinkOverlays::class)->execute($overlayIds);

        return $this->success();
    }
}
