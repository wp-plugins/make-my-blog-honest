<?php

	/* Get a Random Deal */
	$deal = $this->GetRandomDeal();
	
	/* If a deal is returned, print it! */
	if( $deal )
	{

		$str_price = $deal->sale_price;
		
		if(function_exists('money_format'))
		{
		
			$str_price = money_format( '%i', $deal->sale_price ); 
		
		}
			 		
		printf( __( '%s only $%s until %s!', self::PREFIX ), $deal->product_name, $str_price, date('l F jS Y', strtotime( $deal->expires ) ) );
	
	}
	/* Otherwise print our blank message */
	else
	{
	
		_e( 'No deals today, maybe tomorrow!', self::PREFIX );
	
	}
?>