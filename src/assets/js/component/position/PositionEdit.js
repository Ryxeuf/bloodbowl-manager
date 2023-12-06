import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {AutocompleteArrayInput, AutocompleteInput, ReferenceArrayInput, ReferenceInput} from "react-admin";

export const PositionEdit = props => (
    <EditGuesser {...props}>
        <InputGuesser source={"name"}/>
        <InputGuesser source={"min"}/>
        <InputGuesser source={"max"}/>
        <InputGuesser source={"m"}/>
        <InputGuesser source={"f"}/>
        <InputGuesser source={"ag"}/>
        <InputGuesser source={"cp"}/>
        <InputGuesser source={"ar"}/>
        <InputGuesser source={"cost"}/>
        <ReferenceInput source={"faction"} reference={'factions'} name={'faction'}>
            <AutocompleteInput optionText="name" name={'faction_name'}/>
        </ReferenceInput>
        <ReferenceArrayInput name={'skills'} source={'skills'} reference={'skills'}>
            <AutocompleteArrayInput name={'skills_name'} optionText={'name'}/>
        </ReferenceArrayInput>
    </EditGuesser>
);