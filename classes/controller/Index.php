<?php

namespace SkyNetFront\Controller;

use SkyNetFront\Core\Controller;
use SkyNetFront\Core\View;
use SkyNetFront\Model\Tariff;
use SkyNetFront\HTTP\Exception\Exception404;

class Index extends Controller {

    private $isAjax;

    public function prepare()
    {
        $this->isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function action_index()
    {
        $this->loadDataIfRequired();

        $tariffs = $this->session->get('tariffs');

        $view = new View('Index');
        $view->set('tariffs', $tariffs);

        $template = new View('Template');
        $template->set('content', $view->render());

        $this->outputHtml($template);
    }

    public function action_tariff($param = null)
    {
        $this->loadDataIfRequired();

        $tariffId = intval($param);
        $tariffs = $this->session->get('tariffs');

        if (!isset($tariffs[ $tariffId - 1 ]))
        {
            if ($this->isAjax)
            {
                $this->outputJson([
                    'status' => 'error',
                    'errors' => [
                        [ 'message' => 'Tariff not found' ],
                    ],
                ], 404);
            }
            else
            {
                throw new Exception404();
            }

            return;
        }

        $tariff = $tariffs[ $tariffId - 1 ];

        $view = new View('Tariff');
        $view->set('tariff', $tariff);
        $view->set('back_url', '/');

        if ($this->isAjax)
        {
            // AJAX. Build part of HTML page and output it

            $response = [
                'status' => 'ok',
                'content' => $view->render(),
            ];

            $this->outputJson($response);
        }
        else
        {
            // Regular GET. Output the whole page

            $template = new View('Template');
            $template->set('content', $view->render());

            $this->outputHtml($template);
        }
    }

    public function action_plan($param = null)
    {
        $this->loadDataIfRequired();

        $planId = intval($param);

        $plansToTariffs = $this->session->get('plansToTariffs');

        if (!isset($plansToTariffs[ $planId ]))
        {
            if ($this->isAjax)
            {
                $this->outputJson([
                    'status' => 'error',
                    'errors' => [
                        [ 'message' => 'Plan not found' ],
                    ],
                ], 404);
            }
            else
            {
                throw new Exception404();
            }

            return;
        }

        $tariff = $plansToTariffs[ $planId ];
        $plans = $tariff->field('plans');
        $plan = $plans[ $planId ];

        // Get end date

        preg_match('/(\d+)((?:\+|-)[a-z0-9:]+)/', $plan->field('new_payday'), $endDateGroups);
        $endDateTimestamp = $endDateGroups[1];
        $endDateTimezone = $endDateGroups[2];

        $endDate = new \DateTime(strftime('%Y-%m-%d', $endDateTimestamp), new \DateTimeZone($endDateTimezone));

        $view = new View('Tariff/Plan');

        $view->set([
            'tariff' => $tariff,
            'plan' => $plan,
            'start_label' => sprintf( _('will take effect &ndash; %s'), _('today') ),
            'end_label' => sprintf( _('active until &ndash; %s'), $endDate->format('d.m.Y') ),
            'back_url' => $tariff->field('url'),
        ]);

        if ($this->isAjax)
        {
            // AJAX. Build part of HTML page and output it

            $response = [
                'status' => 'ok',
                'content' => $view->render(),
            ];

            $this->outputJson($response);
        }
        else
        {
            // Regular GET. Output the whole page

            $template = new View('Template');
            $template->set('content', $view->render());

            $this->outputHtml($template);
        }
    }

    private function outputHtml(View $view)
    {
        echo $view->render();
    }

    private function outputJson(array $data, $status = 200)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);
        echo json_encode($data);
    }

    private function loadDataIfRequired()
    {
        if (
            $this->session->has('tariffs') &&
            $this->session->has('plansToTariffs')
        ) return;

        $ctx = stream_context_create([
            'http'=> [
                'timeout' => getenv('DATA_TIMEOUT'),
            ],
        ]);

        $data = null;

        try
        {
            $data = file_get_contents(getenv('DATA_URL'), false, $ctx);
        }
        catch (\Exception $e)
        {
            // TODO: Log it maybe
        }

        if (isset($data) && !!$data)
        {
            $data = json_decode($data, true);
            $data = $data['tarifs'];
        }
        else
        {
            $data = [];
        }

        $models = Tariff::processLoadedData($data);

        $this->session->set('tariffs', $models['tariffs']);
        $this->session->set('plansToTariffs', $models['plansToTariffs']);
    }

}