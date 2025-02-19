<?php

namespace App\Controller\ExchangeRate;

use App\ApiDoc\Attributes\NotFoundResponse;
use App\ApiDoc\Attributes\OkResponse;
use App\ApiDoc\Attributes\UnprocessableEntityResponse;
use App\Entity\Currency\Currency;
use App\Entity\Currency\Field\Code;
use App\Service\Validator\Http\ValidatorInterface;
use App\UseCase\Query\ExchangeRate\FetchLastOrRange\FetchLastOrRangeFetcherInterface;
use App\UseCase\Query\ExchangeRate\FetchLastOrRange\Query;
use DateTimeImmutable;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/exchange-rate/{base}/{quote}', name: 'exchange_rate', methods: ['GET'])]
final class ExchangeRateAction extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly FetchLastOrRangeFetcherInterface $fetcher,
    ) {}

    #[OA\Get(
        description: 'List exchange rates of currency pair.',
        summary: 'Currency pair exchange rates.',
        tags: ['Exchange Rate'],
        parameters: [
            new OA\QueryParameter(
                name: 'from',
                description: 'One of format: [Y-m-d, Y-m-d H, Y-m-d H:i]',
                schema: new OA\Schema(type: 'string'),
                example: '2025-01-01 00:00'
            ),
            new OA\QueryParameter(
                name: 'to',
                description: 'One of format: [Y-m-d, Y-m-d H, Y-m-d H:i]',
                schema: new OA\Schema(type: 'string'),
                example: '2025-01-01 23:59'
            ),
            new OA\PathParameter(
                name: 'base',
                description: 'Base currency code',
                schema: new OA\Schema(type: 'string', enum: Code::CODES),
                example: 'USD'
            ),
            new OA\PathParameter(
                name: 'quote',
                description: 'Quote currency code',
                schema: new OA\Schema(type: 'string', enum: Code::CODES),
                example: 'EUR'
            ),
        ],
        responses: [
            new OkResponse(content: new Model(type: Output::class)),
            new NotFoundResponse(description: 'Invalid currency code'),
            new UnprocessableEntityResponse(),
        ]
    )]
    public function __invoke(
        #[MapEntity(mapping: ['base' => 'code'], message: 'Invalid base currency code')]
        Currency $base,
        #[MapEntity(mapping: ['quote' => 'code'], message: 'Invalid quote currency code')]
        Currency $quote,
        #[MapQueryString]
        Input $input,
    ): Response {
        $this->validator->validate($input);

        $query = new Query(
            $baseCode = $base->getCode(),
            $quoteCode = $quote->getCode(),
            $input->from ? new DateTimeImmutable($input->from) : null,
            $this->createTo($input->to)
        );

        $pair = \sprintf('%s/%s', $baseCode, $quoteCode);
        $rates = $this->fetcher->fetch($query);

        return $this->json(Output::fromExchangeRates($pair, $rates));
    }

    private function createTo(?string $datetime): ?DateTimeImmutable
    {
        if (null === $datetime) {
            return null;
        }

        $from = new DateTimeImmutable($datetime);
        $hour = (int) $from->format('H');
        $minute = (int) $from->format('i');

        return $from->setTime($hour, $minute, 59);
    }
}
