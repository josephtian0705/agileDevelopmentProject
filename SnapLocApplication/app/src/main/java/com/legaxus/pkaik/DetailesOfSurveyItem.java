package com.legaxus.pkaik;

import java.util.List;

public class DetailesOfSurveyItem {
	private List<ValuesItem> valuesItems;
	private String lable;
	private String type;
	private String values;
	private String subtype;

	public String getSubtype() {
		return subtype;
	}

	public void setSubtype(String subtype) {
		this.subtype = subtype;
	}

	public String getValues() {
		return values;
	}

	public void setValues(String values) {
		this.values = values;
	}

	public void setLable(String lable){
		this.lable = lable;
	}

	public String getLable(){
		return lable;
	}

	public void setType(String type){
		this.type = type;
	}

	public String getType(){
		return type;
	}

	public List<ValuesItem> getValuesItems() {
		return valuesItems;
	}

	public void setValuesItems(List<ValuesItem> valuesItems) {
		this.valuesItems = valuesItems;
	}



}