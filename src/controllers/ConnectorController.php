<?php
namespace yii2jodit\controllers;

use yii\resp\Controller;
use yii\web\Response;
use yii2jodit\JoditAction;

class ConnectorController extends Controller {
	public $enableCsrfValidation = false;
	/**
	 * @var \yii2jodit\JoditModule
	 */
	public $module;

	public function behaviors() {
		return [
			[
				'class' => 'yii\filters\ContentNegotiator',
				'formats' => [
					'application/json' => Response::FORMAT_JSON
				],
			]
		];
	}

	public function actions() {
		$actions = [];
		$methods =  get_class_methods($this->module->joditApplication);

		foreach ($methods as $method) {
			if (preg_match('#^action([A-Z]+[a-z]+)$#', $method, $match)) {
				$actions[strtolower($match[1])] = [
					'class' => JoditAction::className(),
					'joditApplication' => $this->module->joditApplication,
					'method' => $method,
				];
			}
		}

		return $actions;
	}
}